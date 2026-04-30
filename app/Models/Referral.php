<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class, "user_id");
    }
    
    public function referredBy(){
        return $this->belongsTo(User::class, "referred_by_id");
    }

    public function client(){
        return $this->belongsTo(Client::class, "client_id");
    }

    protected $fillable = [
        'date',
        'referred_by_id',
        'client_id',
        'user_id',
    ];
}
