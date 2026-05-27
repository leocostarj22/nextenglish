<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonModule;
use App\Models\UserLessonProgress;
use App\Models\UserStats;
use App\Services\BadgeService;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    private const CEFR_ORDER = ['A1' => 1, 'A2' => 2, 'B1' => 3, 'B2' => 4, 'C1' => 5, 'C2' => 6];

    public function __construct(private readonly BadgeService $badges) {}

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $modules = LessonModule::with('lessons')->orderBy('cefr_level')->orderBy('order')->get();

        $lessonIds = $modules->flatMap(fn ($m) => $m->lessons->pluck('id'));
        $progressMap = UserLessonProgress::query()
            ->where('user_id', $userId)
            ->whereIn('lesson_id', $lessonIds)
            ->get()
            ->keyBy('lesson_id');

        $stats = UserStats::firstOrCreate(
            ['user_id' => $userId],
            ['total_xp' => 0, 'current_streak' => 0, 'longest_streak' => 0, 'current_cefr_level' => 'A1']
        );

        $cefrOrder = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'];
        $levels = [];

        foreach ($cefrOrder as $code) {
            $levelModules = $modules->where('cefr_level', $code)->values();
            if ($levelModules->isEmpty()) {
                $levels[] = ['code' => $code, 'modules' => []];
                continue;
            }

            $modulesData = $levelModules->map(function ($module) use ($progressMap, $stats) {
                $lessons = $module->lessons;
                $completedCount = $lessons->filter(
                    fn ($l) => ($progressMap[$l->id]?->status ?? 'not_started') === 'completed'
                )->count();

                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'min_xp_to_unlock' => $module->min_xp_to_unlock,
                    'is_unlocked' => $stats->total_xp >= $module->min_xp_to_unlock
                        || $this->isUnlockedByPlacement($module->cefr_level, $stats->placement_level),
                    'total_lessons' => $lessons->count(),
                    'completed_lessons' => $completedCount,
                    'lessons' => $lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'order' => $lesson->order,
                        'title' => $lesson->title,
                        'objective' => $lesson->objective,
                        'xp_reward' => $lesson->xp_reward,
                        'status' => $progressMap[$lesson->id]?->status ?? 'not_started',
                        'xp_earned' => $progressMap[$lesson->id]?->xp_earned ?? 0,
                    ])->values(),
                ];
            });

            $levels[] = ['code' => $code, 'modules' => $modulesData];
        }

        return response()->json([
            'levels' => $levels,
            'user_stats' => [
                'total_xp' => $stats->total_xp,
                'current_streak' => $stats->current_streak,
                'longest_streak' => $stats->longest_streak,
                'current_level' => $stats->current_cefr_level,
            ],
        ]);
    }

    public function show(Request $request, Lesson $lesson)
    {
        $userId = $request->user()->id;
        $lesson->load(['exercises', 'module']);

        $progress = UserLessonProgress::query()
            ->where('user_id', $userId)
            ->where('lesson_id', $lesson->id)
            ->first();

        $exercises = $lesson->exercises->map(fn ($exercise) => [
            'id' => $exercise->id,
            'order' => $exercise->order,
            'type' => $exercise->type,
            'prompt' => $exercise->prompt,
            'options' => $exercise->options,
            'xp_reward' => $exercise->xp_reward,
        ])->values();

        return response()->json([
            'id' => $lesson->id,
            'title' => $lesson->title,
            'objective' => $lesson->objective,
            'grammar_point' => $lesson->grammar_point,
            'intro_text' => $lesson->intro_text,
            'vocabulary' => $lesson->vocabulary,
            'examples' => $lesson->examples,
            'tips' => $lesson->tips,
            'xp_reward' => $lesson->xp_reward,
            'cefr_level' => $lesson->module->cefr_level,
            'module_title' => $lesson->module->title,
            'exercises' => $exercises,
            'user_progress' => [
                'status' => $progress?->status ?? 'not_started',
                'xp_earned' => $progress?->xp_earned ?? 0,
                'correct_answers' => $progress?->correct_answers ?? 0,
                'total_answers' => $progress?->total_answers ?? 0,
            ],
        ]);
    }

    public function start(Request $request, Lesson $lesson)
    {
        $progress = UserLessonProgress::firstOrCreate(
            ['user_id' => $request->user()->id, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress']
        );

        if ($progress->status === 'not_started') {
            $progress->update(['status' => 'in_progress']);
        }

        return response()->json(['ok' => true]);
    }

    public function complete(Request $request, Lesson $lesson)
    {
        $userId = $request->user()->id;

        $progress = UserLessonProgress::firstOrCreate(
            ['user_id' => $userId, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress']
        );

        $bonusXp = 0;
        $newBadges = [];

        if ($progress->status !== 'completed') {
            $bonusXp = $lesson->xp_reward;
            $progress->update([
                'status' => 'completed',
                'completed_at' => now(),
                'xp_earned' => $progress->xp_earned + $bonusXp,
            ]);

            $stats = UserStats::firstOrCreate(
                ['user_id' => $userId],
                ['total_xp' => 0, 'current_streak' => 0, 'longest_streak' => 0, 'current_cefr_level' => 'A1']
            );
            $stats->increment('total_xp', $bonusXp);
            $stats->refresh();

            $this->badges->addWeeklyXp($userId, $bonusXp);

            $lesson->load('module');
            $moduleId = $lesson->module_id;
            $cefrLevel = $lesson->module->cefr_level;

            $newBadges = array_merge(
                $this->badges->awardForModuleComplete($userId, $moduleId, $cefrLevel),
                $this->badges->awardForXp($userId, $stats->total_xp)
            );
        }

        return response()->json(['ok' => true, 'bonus_xp' => $bonusXp, 'new_badges' => $newBadges]);
    }

    private function isUnlockedByPlacement(?string $moduleCefrLevel, ?string $placementLevel): bool
    {
        if ($placementLevel === null || $moduleCefrLevel === null) {
            return false;
        }

        return (self::CEFR_ORDER[$moduleCefrLevel] ?? 0) <= (self::CEFR_ORDER[$placementLevel] ?? 0);
    }

    public function stats(Request $request)
    {
        $userId = $request->user()->id;
        $stats = UserStats::firstOrCreate(
            ['user_id' => $userId],
            ['total_xp' => 0, 'current_streak' => 0, 'longest_streak' => 0, 'current_cefr_level' => 'A1']
        );

        return response()->json([
            'total_xp' => $stats->total_xp,
            'current_streak' => $stats->current_streak,
            'longest_streak' => $stats->longest_streak,
            'current_level' => $stats->current_cefr_level,
            'last_activity_date' => $stats->last_activity_date?->toDateString(),
        ]);
    }
}
