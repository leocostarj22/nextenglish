<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonModule extends Model
{
    protected $fillable = ['cefr_level', 'order', 'title', 'description', 'min_xp_to_unlock'];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id')->orderBy('order');
    }
}
