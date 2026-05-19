<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    protected $fillable = [
        "state",
        "status",
        "reference",
        "content",
        "client_id",
        "sale_id",
        "receipt_id",
    ];
}
