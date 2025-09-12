<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountingAccountResource;
use App\Models\AccountingAccount;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function create(){
         $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
         $accounts = AccountingAccount::where("special_type","WALLET")->orderBy("name", "asc")->get();

        return response()->json(
            [
                "paymentMethods" => $payment_methods,
                "accounts" => AccountingAccountResource::collection($accounts)
            ]
        );
        
    }
}
