<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingRecord extends Model
{
    use HasFactory, SoftDeletes;

    public function alternateRecord()
    {
        return $this->belongsTo(AccountingRecord::class, 'accounting_record_id')->withTrashed();
    }

    public function accountingAccount()
    {
        return $this->belongsTo(AccountingAccount::class, 'accounting_account_id');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }
    public function receiptSummary()
    {
        return $this->belongsTo(ReceiptSummary::class, 'receipt_summary_id');
    }

    public function requestFormItem()
    {
        return $this->belongsTo(RequestFormItem::class, 'request_form_item_id');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
    public function production()
    {
        return $this->belongsTo(Production::class, 'production_id');
    }

    public function summary()
    {
        return $this->belongsTo(Summary::class, 'summary_id');
    }

    public function siteSaleSummary()
    {
        return $this->belongsTo(SiteSaleSummary::class, 'site_sale_summary_id');
    }

    public function records()
    {
        return $this->hasMany(AccountingRecord::class);
    }


    protected $fillable = [
        'serial',
        'reference',
        'date',
        "name",
        'description',
        'amount',
        'opening_balance',
        'closing_balance',
        'type', // 'debit' or 'credit'
        'accounting_account_id',
        'accounting_record_id',
        'request_form_item_id',
        'summary_id',
        'site_sale_summary_id',
        "receipt_id",
        "receipt_summary_id",
        "production_id",
        "collection_id",
    ];
}
