<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuildingTip extends Model
{
    protected $table = 'building_tips';

    protected $fillable = [
        'title',
        'body',
        'category',
        'photo',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
