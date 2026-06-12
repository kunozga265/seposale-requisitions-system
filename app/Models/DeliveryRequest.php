<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    use HasFactory;

    public function __get($name)
    {
        if ($name === 'paid') {
            if ($this->payment?->status == 'SUCCESS') {
                return true;
            } else if ($this->paymentReceipt != null && $this->receipt != null) {
                return true;
            } else {
                return false;
            }
        }


        // It's important to call the parent __get() method
        // to allow other properties to be accessed normally.
        return parent::__get($name);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function summary()
    {
        return $this->belongsTo(Summary::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function transportOption()
    {
        return $this->belongsTo(TransportOption::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function paymentReceipt()
    {
        return $this->belongsTo(PaymentReceipt::class);
    }

    protected $fillable = [
        'sale_id',
        'summary_id',
        'delivery_id',
        'transport_option_id',
        'trips',
        'amount',
        'quantity',
        'status',
        'serial',
        'payment_id',
        'payment_receipt_id',
        'receipt_id',
        'active',
    ];
}
