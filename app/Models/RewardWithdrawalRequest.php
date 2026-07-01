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
        'approved_by',
        'approved_at',
        'paid_by',
        'paid_at',
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

    public function rewards()
    {
        return $this->hasMany(ClientReward::class, 'reward_withdrawal_request_id');
    }
}
