<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    protected $fillable=[
        "date",
        "reference",
        "description",
        "from_to",
        "expense_id",
        "receipt_id",
        "account_id",
        "total",
        "balance",
        "type",
    ];
}
