<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyChallenge extends Model
{
    protected $fillable = ['title', 'description', 'xp_goal', 'week_start', 'reward_xp'];

    protected $casts = ['week_start' => 'date'];
}
