<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    protected $fillable=[
        "name",
        "number",
        "branch",
        "type",
        "balance",
    ];
}
