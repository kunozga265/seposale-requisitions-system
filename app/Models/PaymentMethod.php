<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "photo",
        "for_withdrawal",
        "withdrawal_fields",
    ];

    protected $casts = [
        'for_withdrawal'   => 'boolean',
        'withdrawal_fields' => 'array',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];
}
