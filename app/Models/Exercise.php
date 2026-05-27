<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exercise extends Model
{
    protected $fillable = [
        'lesson_id', 'order', 'type', 'prompt', 'options',
        'correct_answer', 'explanation', 'xp_reward',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
