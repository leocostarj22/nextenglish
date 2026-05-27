<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = ['slug', 'name', 'description', 'icon', 'condition_type', 'condition_value'];
}
