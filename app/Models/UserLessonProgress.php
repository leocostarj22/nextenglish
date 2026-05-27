<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLessonProgress extends Model
{
    protected $fillable = [
        'user_id', 'lesson_id', 'status', 'xp_earned',
        'correct_answers', 'total_answers', 'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
