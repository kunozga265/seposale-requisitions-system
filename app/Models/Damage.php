<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;

    protected $fillable = [
        "batch_id",
        "inventory_id",
        "production_id",
        "quantity",
        "date",
        "cost",
    ];
}
