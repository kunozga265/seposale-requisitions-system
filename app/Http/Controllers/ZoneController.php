<?php

namespace App\Http\Controllers;


use App\Http\Resources\ClientResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\QuotationResource;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\SiteSaleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ZoneLiteResource;
use App\Http\Resources\ZoneResource;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Sale;
use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\SiteSale;
use App\Models\Collection;
use App\Models\User;
use App\Models\CustomJob;
use Illuminate\Support\Carbon;
use App\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsCheckImport;
use App\Models\Zone;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $zones = Zone::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ZoneResource::collection($zones));
        else {
            //Web Response
            return Inertia::render('Zones/Index', [
                'zones' => ZoneLiteResource::collection($zones),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $zone = Zone::find($id);

        if (is_object($zone)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ZoneResource($zone));
            } else {

                //Web Response
                return Inertia::render('Zones/Show', [
                    'zone' => new ZoneResource($zone),
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Zone not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Zone not found');
            }
        }
    }


    public function create(Request $request)
    {

        return Inertia::render('Zones/Create', []);
    }


    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'coordinates' => ['required'],
            // 'cost' => ['required'],
            // 'level' => ['required'],
        ]);

        $zone = Zone::create([
            'name' => ucwords($request->name),
            'cost' => floatval($request->cost),
            'level' => intval($request->level ?? 1),
            'coordinates' => json_encode($request->coordinates),
            'country_id' => config('branch.default_country_id'),
        ]);

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ZoneResource($zone), 201);
        else {
            //Web Response
            return Redirect::route('zones.index')->with('success', 'Zone created!');
        }
    }


    public function edit(Request $request, $id)
    {
        $zone = Zone::find($id);

        if (is_object($zone)) {

            return Inertia::render('Zones/Edit', [
                'zone' => new ZoneResource($zone),
            ]);
        } else {
            return Redirect::back()->with('error', 'Zone not found');
        }
    }



    public function update(Request $request, $id)
    {

        $zone = Zone::find($id);

        if (is_object($zone)) {

            //Validate all the important attributes
            $request->validate([
                'name' => ['required'],
                'coordinates' => ['required'],
                // 'cost' => ['required'],
                // 'level' => ['required'],
            ]);

            $zone->update([
                'name' => ucwords($request->name),
                'cost' => floatval($request->cost),
                'level' => intval($request->level ?? 1),
                'coordinates' => json_encode($request->coordinates),
            ]);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new ZoneResource($zone), 201);
            else {
                //Web Response
                return Redirect::route('zones.index')->with('success', 'Zone updated!');
            }
        } else {
            return Redirect::back()->with('error', 'Zone not found');
        }
    }
    public function destroy(Request $request, $id)
    {

        $zone = Zone::find($id);

        if (is_object($zone)) {

           $zone->delete();


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new ZoneResource($zone), 201);
            else {
                //Web Response
                return Redirect::route('zones.index')->with('success', 'Zone deleted!');
            }
        } else {
            return Redirect::back()->with('error', 'Zone not found');
        }
    }


}
