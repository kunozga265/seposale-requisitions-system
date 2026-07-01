<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientReward extends Model
{
    protected $table = 'client_rewards';

    protected $fillable = [
        'client_id',
        'product_variant_id',
        'sale_id',
        'receipt_id',
        'reward_withdrawal_request_id',
        'amount',
        'type',
        'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function withdrawalRequest()
    {
        return $this->belongsTo(RewardWithdrawalRequest::class, 'reward_withdrawal_request_id');
    }
}
