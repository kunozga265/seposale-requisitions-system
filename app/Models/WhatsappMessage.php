<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    public $table = "whatsapp_messages";

    public function __get($name)
    {
        if ($name === 'name') {
            if ($this->client != null) {
                return $this->client->getName();
            } else if ($this->sale != null) {
                return $this->sale->client->getName();
            } else if ($this->invoice != null) {
                return $this->invoice->client->getName();
            } else if ($this->quotation != null) {
                return $this->quotation->client->getName();
            } else if ($this->receipt != null) {
                return $this->receipt->client->getName();
            } else if ($this->delivery != null) {
                return $this->delivery->summary->sale->client->getName();
            } else if ($this->collection != null) {
                return $this->collection->client->getName();
            } else if ($this->supplierVoucher != null) {
                return $this->supplierVoucher->contact->name;
            } else if ($this->creditVoucher != null) {
                return $this->creditVoucher->contact->name;
            } else {
                return "Unknown Contact";
            }
        } else  if ($name === 'status') {
            $last = $this->statuses()->latest()->first();
            if (is_object($last)) {
                return ucfirst($last->status);
            } else {
                return 'Received';
            }
        }

        // It's important to call the parent __get() method
        // to allow other properties to be accessed normally.
        return parent::__get($name);
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
    public function creditVoucher()
    {
        return $this->belongsTo(CreditVoucher::class);
    }
    public function supplierVoucher()
    {
        return $this->belongsTo(SupplierVoucher::class);
    }
    public function requestFormItem()
    {
        return $this->belongsTo(RequestFormItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function statuses()
    {
        return $this->hasMany(WhatsappMessageStatus::class);
    }

    protected $fillable = [
        //ESSENTIAL
        'type',
        // 'status',
        'phone_number',
        'message_type',
        'message',
        'wamid',
        // 'whatsapp_message_type',
        'payload',

        //OPTIONAL
        'client_id',
        'sale_id',
        'quotation_id',
        'invoice_id',
        'receipt_id',
        'delivery_id',
        'collection_id',
        'credit_voucher_id',
        'supplier_voucher_id',
        'request_form_item_id',
        'user_id',
    ];
}
