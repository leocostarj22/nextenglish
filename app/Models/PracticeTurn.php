<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeTurn extends Model
{
    protected $fillable = [
        'practice_session_id',
        'user_id',
        'scenario',
        'question',
        'generated_questions_id',
        'user_input',
        'ai_original_json',
        'corrected',
        'improved',
        'explanation',
        'pronunciation_tip',
        'score',
        'model',
        'tokens_in',
        'tokens_out',
        'latency_ms',
    ];

    protected $casts = [
        'score' => 'integer',
        'tokens_in' => 'integer',
        'tokens_out' => 'integer',
        'latency_ms' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(PracticeSession::class, 'practice_session_id');
    }
}