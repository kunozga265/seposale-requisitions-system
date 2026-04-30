<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{


    public function index(Request $request)
    {
        $user = (new AppController())->getAuthUser($request);
        if (
            $user->hasRole('management') ||
            $user->hasRole('accountant')  ||
            $user->hasRole('sales')
        ) {
            $sales = Sale::orderBy("date", "desc")->paginate((new AppController())->paginate);
        } else {
            $sales = $user->sales()->orderBy("date", "desc")->paginate((new AppController())->paginate);
        }
        return response()->json(SaleResource::collection($sales));
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $sale = sale::withTrashed()->where('id',$id)->orWhere('serial',$id)->first();
        // $payment_methods = PaymentMethod::orderBy("name", "asc")->get();
        // $accounts = Account::all();
        // $accounts = AccountingAccount::where('special_type', 'WALLET')
        // ->orderBy('name', 'asc')
        // ->get();
        // $users = User::orderBy("firstName")->get();

        if (is_object($sale)) {
            return response()->json(new SaleResource($sale));
        } else {
            return response()->json(['message' => "sale not found"], 404);
        }
    }
}
