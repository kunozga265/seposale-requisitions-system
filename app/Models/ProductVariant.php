<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function reward()
    {
        return $this->hasOne(ProductVariantReward::class);
    }

    protected $fillable = [
        "name",
        "slug",
        "product_id",
        "unit",
        "quantity",
        "cost",
        "cost_original",
        "description",
        'description_full',
        'featured',
        'transport_inclusive',
        'specifications',
        'product_information',
        'about',
        'photo',
    ];
}
