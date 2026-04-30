<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

      public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }


    protected $fillable = [
        "serial",
        "code",
        "date",
        "quantity",
        "cost",
        "total",
        "balance",
        "photo",
        "recipient_name",
        "recipient_phone_number",
        "delivery_id",
        // "client_id",
    ];
}
