<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
     

    public function index(Request $request)
    {
        $sales = Sale::orderBy("date", "desc")->paginate((new AppController())->paginate);
        return response()->json(SaleResource::collection($sales));
    }
}
