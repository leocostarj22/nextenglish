<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'order', 'title', 'objective', 'grammar_point',
        'intro_text', 'vocabulary', 'examples', 'tips', 'xp_reward',
    ];

    protected $casts = [
        'vocabulary' => 'array',
        'examples' => 'array',
        'tips' => 'array',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(LessonModule::class, 'module_id');
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class)->orderBy('order');
    }
}
