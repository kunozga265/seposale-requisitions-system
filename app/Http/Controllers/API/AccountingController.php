<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountingAccountResource;
use App\Models\AccountingAccount;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function index(Request $request)
    {

        switch ($request->query('section')) {
            case 'all':
                $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
                $accounts = AccountingAccount::where("special_type", "WALLET")->orderBy("name", "asc")->get();

                $data =  [
                    "paymentMethods" => $payment_methods,
                    "accounts" => AccountingAccountResource::collection($accounts)
                ];
            default:
                $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
                $accounts = AccountingAccount::where("special_type", "WALLET")->orderBy("name", "asc")->get();

                $data =  [
                    "paymentMethods" => $payment_methods,
                    "accounts" => AccountingAccountResource::collection($accounts)
                ];
        }



        return response()->json(
            $data
        );
    }
}
