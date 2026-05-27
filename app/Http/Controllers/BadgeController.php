<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\UserBadge;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $badges = Badge::all();

        $earned = UserBadge::where('user_id', $userId)
            ->get()
            ->keyBy('badge_id');

        $result = $badges->map(fn ($badge) => [
            'id' => $badge->id,
            'slug' => $badge->slug,
            'name' => $badge->name,
            'description' => $badge->description,
            'icon' => $badge->icon,
            'condition_type' => $badge->condition_type,
            'condition_value' => $badge->condition_value,
            'earned' => isset($earned[$badge->id]),
            'earned_at' => $earned[$badge->id]?->earned_at?->toDateString(),
        ]);

        return response()->json(['badges' => $result]);
    }
}
