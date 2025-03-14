<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    protected $fillable = [
        "user_id",
        "date",
        "price",
        "quantity",
        "balance",
        "comments",
        "photo",
        "inventory_id",
        "stock_id",
    ];
}
