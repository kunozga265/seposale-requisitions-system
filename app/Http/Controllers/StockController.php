<?php

namespace App\Http\Controllers;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StockController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required"],
            "units" => ["required"],
            "quantity" => ["required", "numeric", "gt:0"],
            "threshold" => ["required", "numeric", "gt:0"],
            "site_id" => ["required"],
        ]);

        Stock::create([
            "name" => $request->name,
            "units" => $request->units,
            "site_id" => $request->site_id,
            "quantity" => $request->quantity,
            "threshold" => $request->threshold,
        ]);

        if ((new AppController())->isApi($request)) {
            //API Response
//                return response()->json(new SiteResource($site));
        } else {

            //Web Response
            return Redirect::back()->with("success", "New Stock Item added!");
        }
    }
}
