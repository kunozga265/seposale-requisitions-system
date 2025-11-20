<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    public $table = "whatsapp_messages";

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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'type',
        'status',
        'phone_number',
        'message_type',
        'message',
        'whatsapp_message_id',
        'whatsapp_message_type',
        'payload',
        'client_id',
        'sale_id',
        'quotation_id',
        'invoice_id',
        'receipt_id',
        'delivery_id',
        'collection_id',
        'credit_voucher_id',
        'supplier_voucher_id',
    ];
}
