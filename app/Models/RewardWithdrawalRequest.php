<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardWithdrawalRequest extends Model
{
    protected $table = 'reward_withdrawal_requests';

    protected $fillable = [
        'client_id',
        'amount',
        'status',
        'notes',
        'payment_method_id',
        'payout_details',
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
    ];

    protected $casts = [
        'payout_details' => 'array',
    ];

    protected $dates = ['approved_at', 'paid_at'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function rewards()
    {
        return $this->hasMany(ClientReward::class, 'reward_withdrawal_request_id');
    }
}
