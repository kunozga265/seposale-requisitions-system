<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    protected $fillable = [
        "code",
        "client_id",
        "location",
        "information",
        "total",
        "quotes",
        "user_id",
        "sale_id",
    ];
}
