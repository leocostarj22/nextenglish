<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWeeklyProgress extends Model
{
    protected $fillable = ['user_id', 'challenge_id', 'xp_earned', 'completed_at'];

    protected $casts = ['completed_at' => 'datetime'];
}
