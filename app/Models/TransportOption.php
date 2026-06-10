<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportOption extends Model
{
    use HasFactory;

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }


    protected $fillable = [
        'vehicle_type_id',
        'product_id',
        'zone_id',
        'cost',
        'max',
        'available',
        'meta',
    ];
}
