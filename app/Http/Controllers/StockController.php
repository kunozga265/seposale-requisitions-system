<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Http\Resources\StockResource;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class StockController extends Controller
{
    public function index(Request $request,$code)
    {


        $site = Site::where("code",$code)->first();

        if (is_object($site)) {
            $stocks = $site->stocks;




            if ((new AppController())->isApi($request)) {
                //API Response
//                return response()->json(new SiteResource($site));
            } else {

                //Web Response
                return Inertia::render('Sites/Overview', [
                    'site' => new SiteResource($site),
                    'stocks' => StockResource::collection($site->stocks),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }
}
