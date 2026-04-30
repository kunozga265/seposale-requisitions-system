<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpenseResource;
use App\Models\Collection;
use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{

    public function index(Request $request)
    {
       $expenses = Expense::orderBy("date","desc")->get();

//        dd((new AppController())->generateCompound(DeliveryResource::collection($deliveries)));

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ExpenseResource::collection($expenses));
        else {
            //Web Response
            return Inertia::render('Expenses/Index', [
                'expenses' => ExpenseResource::collection($expenses),
            ]);
        }
    }

    public function create(Request $request)
    {
        return Inertia::render('Expenses/Create', [
        ]);
    }

    public function getCodeNumber()
    {
        $last = Expense::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }
}
