<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{

    public function index(Request $request)
    {
       $expense = Expense::orderBy("date","desc")->get();

//        dd((new AppController())->generateCompound(DeliveryResource::collection($deliveries)));

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(DeliveryResource::collection($deliveries));
        else {
            //Web Response
            return Inertia::render('Deliveries/Index', [
//                'deliveries' => (new AppController())->generateCompound(DeliveryResource::collection($deliveries)),
                'deliveries' => DeliveryResource::collection($deliveries),
                'headline'=>$headline
            ]);
        }
    }
}
