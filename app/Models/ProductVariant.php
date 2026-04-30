<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
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
