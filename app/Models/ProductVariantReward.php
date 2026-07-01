<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantReward extends Model
{
    protected $table = 'product_variant_rewards';

    protected $fillable = [
        'product_variant_id',
        'reward_amount',
        'active',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
