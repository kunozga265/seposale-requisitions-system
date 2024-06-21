<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Summary::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    protected $fillable=[
        "code",
        "code_alt",
        "status",
        "client_id",
        "total",
        "balance",
        "date",
        "editable",
        "comments",
        "location",
        "user_id",
    ];
}
