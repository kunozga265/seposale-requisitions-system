<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class, "request_id","id");
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
    }
    public function payee()
    {
        $payee = "-";
        if($this->transporter == null && $this->supplier == null){
            $payee = $this->requestForm->user->fullName();
        }else if($this->transporter != null){
            $payee = $this->transporter->name;
        }else if($this->supplier != null){
            $payee = $this->supplier->name;
        }

        return $payee;
    }

    protected $fillable = [
        "code",
        "description",
        "total",
        "date",
        "contents",
        "expense_type_id",
        "sale_id",
        "delivery_id",
        "request_id",
        "transporter_id",
        "supplier_id",
        "account_id",
        "reference",
    ];
}
