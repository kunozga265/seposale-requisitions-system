<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'product_variant_id',
        'client_id',
        'name',
        'rating',
        'comment',
        'date',
        'verified',
        'location',
        'helpful_count',
        'photos',
        'active',
        'featured',
    ];

    protected $casts = [
        'photos'    => 'array',
        'verified'  => 'boolean',
        'active'    => 'boolean',
        'featured'  => 'boolean',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
