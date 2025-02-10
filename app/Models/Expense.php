<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];
}
