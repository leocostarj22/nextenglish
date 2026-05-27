<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\UserLessonProgress;
use App\Models\UserStats;
use App\Models\UserWeeklyProgress;
use App\Models\WeeklyChallenge;
use Carbon\Carbon;

class BadgeService
{
    public function awardForXp(int $userId, int $totalXp): array
    {
        return $this->checkAndAward($userId, 'xp_milestone', fn ($val) => $totalXp >= (int) $val);
    }

    public function awardForStreak(int $userId, int $streak): array
    {
        return $this->checkAndAward($userId, 'streak', fn ($val) => $streak >= (int) $val);
    }

    public function awardForModuleComplete(int $userId, int $moduleId, string $cefrLevel): array
    {
        $lessonIds = \App\Models\Lesson::where('module_id', $moduleId)->pluck('id');
        $completedCount = UserLessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();

        if ($completedCount < $lessonIds->count()) {
            return [];
        }

        return $this->checkAndAward($userId, 'module_complete', fn ($val) => $val === $cefrLevel);
    }

    public function addWeeklyXp(int $userId, int $xpEarned): void
    {
        if ($xpEarned <= 0) {
            return;
        }

        $weekStart = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        $challenge = WeeklyChallenge::firstOrCreate(
            ['week_start' => $weekStart],
            ['title' => 'Desafio da Semana', 'description' => 'Ganhe XP praticando aulas e exercícios esta semana.', 'xp_goal' => 150, 'reward_xp' => 50]
        );

        $progress = UserWeeklyProgress::firstOrCreate(
            ['user_id' => $userId, 'challenge_id' => $challenge->id],
            ['xp_earned' => 0]
        );

        $progress->increment('xp_earned', $xpEarned);
        $progress->refresh();

        if ($progress->completed_at === null && $progress->xp_earned >= $challenge->xp_goal) {
            $progress->update(['completed_at' => now()]);

            $stats = UserStats::where('user_id', $userId)->first();
            if ($stats) {
                $stats->increment('total_xp', $challenge->reward_xp);
            }
        }
    }

    private function checkAndAward(int $userId, string $conditionType, callable $matcher): array
    {
        $candidates = Badge::where('condition_type', $conditionType)->get();
        $alreadyEarned = UserBadge::where('user_id', $userId)
            ->whereIn('badge_id', $candidates->pluck('id'))
            ->pluck('badge_id')
            ->toArray();

        $newBadges = [];

        foreach ($candidates as $badge) {
            if (in_array($badge->id, $alreadyEarned, true)) {
                continue;
            }

            if ($matcher($badge->condition_value)) {
                UserBadge::create([
                    'user_id' => $userId,
                    'badge_id' => $badge->id,
                    'earned_at' => now(),
                ]);

                $newBadges[] = [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon' => $badge->icon,
                ];
            }
        }

        return $newBadges;
    }
}
