<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFormItem extends Model
{
    use HasFactory;

    public function account (){
        return $this->belongsTo(AccountingAccount::class, "accounting_account_id", "id");
    }

    public function records(){
       return $this->hasMany(AccountingRecord::class);
    }

    public function requestForm(){
       return $this->belongsTo(RequestForm::class,"request_id");
    }

    protected $fillable = [
        "details",
        "units",
        "quantity",
        "unit_cost",
        "total_cost",
        "balance",
        "status",
        "request_id",
        "accounting_account_id",
        "transporter_id",
        "supplier_id",
        "comments",
    ];
}
