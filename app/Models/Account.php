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
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

//    public function transactions()
//    {
//        $transactions = [];
//        foreach ($this->expenses as $expense){
//            $transactions[]=[
//
//            ];
//        }
//    }

    protected $fillable=[
        "name",
        "number",
        "photo",
        "branch",
        "type",
        "balance",
    ];
}
