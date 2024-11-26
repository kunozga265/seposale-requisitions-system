<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Models\Site;
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
                //Web Response
                return Inertia::render('Sites/Overview', [
                    'site' => new SiteResource($site),
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
