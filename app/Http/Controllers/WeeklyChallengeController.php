<?php

namespace App\Http\Controllers;

use App\Models\UserWeeklyProgress;
use App\Models\WeeklyChallenge;
use Illuminate\Http\Request;

class WeeklyChallengeController extends Controller
{
    public function current(Request $request)
    {
        $userId = $request->user()->id;

        $weekStart = now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();

        $challenge = WeeklyChallenge::firstOrCreate(
            ['week_start' => $weekStart],
            [
                'title' => 'Desafio da Semana',
                'description' => 'Ganhe XP praticando aulas e exercícios esta semana.',
                'xp_goal' => 150,
                'reward_xp' => 50,
            ]
        );

        $progress = UserWeeklyProgress::firstOrCreate(
            ['user_id' => $userId, 'challenge_id' => $challenge->id],
            ['xp_earned' => 0]
        );

        $weekEnd = now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(6)->toDateString();

        return response()->json([
            'id' => $challenge->id,
            'title' => $challenge->title,
            'description' => $challenge->description,
            'xp_goal' => $challenge->xp_goal,
            'reward_xp' => $challenge->reward_xp,
            'week_start' => $challenge->week_start->toDateString(),
            'week_end' => $weekEnd,
            'user_progress' => [
                'xp_earned' => $progress->xp_earned,
                'completed' => $progress->completed_at !== null,
            ],
        ]);
    }
}
