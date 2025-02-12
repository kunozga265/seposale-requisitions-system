<?php

namespace App\Models;

use App\Http\Controllers\AppController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
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

    public function formattedCode()
    {
        return (new AppController())->getZeroedNumber($this->code);
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
        "transporter_id",
        "supplier_id",
        "request_id",
        "paid",
    ];
}
