<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'body',
        'sort_order',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
