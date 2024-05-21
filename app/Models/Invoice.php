<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        "code",
        "name",
        "phone_number",
        "email",
        "address",
        "location",
        "information",
        "total",
        "receipts",
        "user_id",
        "status",
    ];
}
