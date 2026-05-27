<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementTestQuestion extends Model
{
    protected $fillable = ['cefr_level', 'order', 'question', 'options', 'correct_answer', 'explanation'];

    protected $casts = ['options' => 'array'];
}
