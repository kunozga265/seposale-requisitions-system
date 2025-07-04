<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingRecord extends Model
{
    use HasFactory;

    public function alternateRecord()
    {
        return $this->belongsTo(AccountingRecord::class, 'accounting_record_id');
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
        "production_id",
        "collection_id",
    ];
}
