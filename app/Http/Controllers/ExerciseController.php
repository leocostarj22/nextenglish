<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\UserLessonProgress;
use App\Models\UserStats;
use App\Services\AiCoachService;
use App\Services\BadgeService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use RuntimeException;

class ExerciseController extends Controller
{
    public function __construct(
        private readonly AiCoachService $ai,
        private readonly BadgeService $badges,
    ) {}

    public function submit(Request $request, Exercise $exercise)
    {
        $data = $request->validate([
            'answer' => ['required', 'string', 'max:2000'],
        ]);

        $userId = $request->user()->id;
        $lesson = $exercise->lesson()->with('module')->first();

        if ($exercise->type === 'free_write') {
            set_time_limit((int) env('AI_MAX_EXECUTION_TIME', 180));

            $cefrLevel = $lesson?->module?->cefr_level ?? 'A1';

            try {
                $ai = $this->ai->evaluateSpeaking(
                    $data['answer'],
                    "Lesson exercise ({$cefrLevel}): {$exercise->prompt}",
                    'pt-BR'
                );
            } catch (ConnectionException $e) {
                report($e);

                return response()->json([
                    'message' => 'Não foi possível conectar ao provedor de I.A.',
                ], 502);
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
                if (preg_match('/^AI gateway error \((\d+)\):\s*(.*)$/s', $message, $m)) {
                    $status = (int) $m[1];
                    $body = trim($m[2]);
                    $decoded = json_decode($body, true);

                    return response()->json([
                        'message' => (string) (data_get($decoded, 'error.message') ?? data_get($decoded, 'message') ?? $body ?: $message),
                    ], $status);
                }

                return response()->json(['message' => $message], 500);
            }

            $newBadges = $this->recordAnswer($userId, $exercise->lesson_id, true, $exercise->xp_reward);

            return response()->json([
                'type' => 'free_write',
                'is_correct' => true,
                'xp_earned' => $exercise->xp_reward,
                'ai_feedback' => $ai['result'],
                'new_badges' => $newBadges,
            ]);
        }

        $isCorrect = $this->checkAnswer($exercise, $data['answer']);
        $xpEarned = $isCorrect ? $exercise->xp_reward : 0;

        $newBadges = $this->recordAnswer($userId, $exercise->lesson_id, $isCorrect, $xpEarned);

        return response()->json([
            'type' => $exercise->type,
            'is_correct' => $isCorrect,
            'correct_answer' => $exercise->correct_answer,
            'explanation' => $exercise->explanation,
            'xp_earned' => $xpEarned,
            'new_badges' => $newBadges,
        ]);
    }

    private function checkAnswer(Exercise $exercise, string $userAnswer): bool
    {
        $correct = mb_strtolower(trim($exercise->correct_answer ?? ''));
        $given = mb_strtolower(trim($userAnswer));

        if ($exercise->type === 'order_sentence') {
            $correct = (string) preg_replace('/[^a-z0-9\s]/u', '', $correct);
            $given = (string) preg_replace('/[^a-z0-9\s]/u', '', $given);

            return preg_replace('/\s+/', ' ', trim($correct)) === preg_replace('/\s+/', ' ', trim($given));
        }

        return $correct === $given;
    }

    private function recordAnswer(int $userId, int $lessonId, bool $isCorrect, int $xpEarned): array
    {
        $progress = UserLessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lessonId],
            ['status' => 'in_progress']
        );

        $progress->increment('total_answers');
        if ($isCorrect) {
            $progress->increment('correct_answers');
        }
        if ($xpEarned > 0) {
            $progress->increment('xp_earned', $xpEarned);
        }

        $stats = UserStats::firstOrCreate(
            ['user_id' => $userId],
            ['total_xp' => 0, 'current_streak' => 0, 'longest_streak' => 0, 'current_cefr_level' => 'A1']
        );

        if ($xpEarned > 0) {
            $stats->increment('total_xp', $xpEarned);
        }

        $today = now()->toDateString();
        $lastDate = $stats->last_activity_date?->toDateString();

        if ($lastDate !== $today) {
            $yesterday = now()->subDay()->toDateString();
            $newStreak = ($lastDate === $yesterday) ? $stats->current_streak + 1 : 1;
            $newLongest = max($newStreak, $stats->longest_streak);

            $stats->update([
                'current_streak' => $newStreak,
                'longest_streak' => $newLongest,
                'last_activity_date' => $today,
            ]);

            $stats->refresh();
        }

        $newBadges = [];

        if ($xpEarned > 0) {
            $this->badges->addWeeklyXp($userId, $xpEarned);
            $stats->refresh();
            $newBadges = array_merge(
                $newBadges,
                $this->badges->awardForXp($userId, $stats->total_xp)
            );
        }

        $newBadges = array_merge(
            $newBadges,
            $this->badges->awardForStreak($userId, $stats->current_streak)
        );

        return $newBadges;
    }
}
