<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SiteResource;
use App\Http\Resources\SiteSaleSummaryResource;
use App\Models\Product;
use App\Models\Site;
use App\Models\SiteSaleSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SiteController extends Controller
{
    public function overview(Request $request, $code)
    {
        $site = Site::where("code",$code)->first();

        if (is_object($site)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new SiteResource($site));
            } else {
                //get products
                $products = Product::orderBy("name","asc")->get();

                //Web Response
                return Inertia::render('Sites/Overview', [
                    'site' => new SiteResource($site),
                    'collections' => $site->pendingCollections(),
                    "products" => ProductResource::collection($products),
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
