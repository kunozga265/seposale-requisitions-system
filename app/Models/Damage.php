<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;


    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    protected $fillable = [
        "batch_id",
        "inventory_id",
        "production_id",
        "quantity",
        "date",
        "cost",
    ];
}
