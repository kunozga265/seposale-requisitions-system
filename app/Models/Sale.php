<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function client(): BelongsTo
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

    public function pops()
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    public function payables()
    {
        return $this->hasMany(Payable::class);
    }

    public function expense()
    {
        return $this->hasOne(Expense::class);
    }


    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
    public function notes()
    {
        return $this->hasOne(Quotation::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code_alt);
    }

    public function profit()
    {
        $sum = 0;
        foreach ($this->products as $summary) {
            $sum += $summary->profit();
        }
        return $sum;
    }

    public function attachedReceipts()
    {
        return $this->belongsToMany(Receipt::class, 'receipt_sale', 'sale_id', 'receipt_id');
    }

    public function deliveries()
{
    return $this->hasManyThrough(
        Delivery::class,
        Summary::class,
        'sale_id',
        'summary_id',
        'id',
        'id'
    );
}

// public function deliveryNotes()
// {
//     return DeliveryNote::whereHas('delivery', function ($q) {
//         $q->whereIn('id', $this->deliveries()->pluck('id'));
//     });
// }

public function deliveryNotes()
{
    return DeliveryNote::query()
        ->whereHas('delivery.summary', function ($q) {
            $q->where('sale_id', $this->id);
        });
}

 /**
     * Get all collections related to this sale
     */
    public function collections()
    {
        return \App\Models\Collection::whereIn(
            'site_sale_summary_id',
            $this->products()
                ->whereNotNull('site_sale_summary_id')
                ->pluck('site_sale_summary_id')
        );
    }


    protected $fillable = [
        "code",
        "serial",
        "code_alt",
        "status",
        "client_id",
        "total",
        "balance",
        "date",
        "editable",
        "comments",
        "location",
        "recipient_name",
        "recipient_profession",
        "recipient_phone_number",
        "user_id",
        "whatsapp",
        "local_purchase_order",
    ];
}
