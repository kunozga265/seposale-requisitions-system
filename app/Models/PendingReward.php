<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A reward earned at payment time, staged here instead of directly in
 * ClientReward -- admin/'s PendingRewardController is where staff assign it
 * to the client and/or the sale's rightful agent(s).
 */
class PendingReward extends Model
{
    protected $table = 'pending_rewards';

    const STATUS_PENDING = 'pending';

    protected $fillable = [
        'client_id',
        'sale_id',
        'receipt_id',
        'product_variant_id',
        'amount',
        'date',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
