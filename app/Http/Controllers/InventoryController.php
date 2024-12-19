<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\SiteSaleSummaryResource;
use App\Models\Inventory;
use App\Models\Site;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code",$code)->first();

        if(is_object($site)){
            //
            $inventory = $site->inventories()->where("id",$id)->first();

            $summaries = $inventory->summaries;
            $sales = [];
            foreach ($summaries as $summary){
                $sales[]= [
                  "id"=>$summary->sale->id,
                  "code"=>(new AppController())->getZeroedNumber($summary->sale->code),
                  "client"=>$summary->sale->client,
                  "date"=>$summary->sale->date,
                  "products"=>[
                      new SiteSaleSummaryResource($summary)
                  ],
                ];
            }

            if (is_object($inventory)){
                return Inertia::render('Inventories/Show', [
                    "site" => $site,
                    "inventory" => new InventoryResource($inventory),
                    "sales" => $sales,
                    "batches" => [],
                    "collections" => CollectionResource::collection($inventory->collections),
                ]);

            }else {
                if ((new AppController())->isApi($request)) {
                    //API Response
                    return response()->json(['message' => "Daily Report not found"], 404);
                } else {
                    //Web Response
                    return Redirect::route('dashboard')->with('error', 'Daily Report not found');
                }
            }

        }else{
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Site not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Site not found');
            }
        }
    }

    public function update(Request $request)
    {

        $inventory=Inventory::find($request->inventory_id);

        if(is_object($inventory)){

            //Validate all the important attributes
            $request->validate([
                'quantity' => ['required'],
                'date' => ['required'],
            ]);

            $availableStock = $inventory->available_stock + $request->quantity;

            $inventory->update([
                'available_stock' => $availableStock
            ]);

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => "New Stock! Added {$request->quantity} to {$inventory->name}",
                "inventory_id" => $inventory->id,
                "contents" => json_encode([
                    "date" => $request->date,
                    "quantity" => $request->quantity,
                    "comments" => $request->comments,
                    "photo" => $request->photo,
                ])
            ]);

            //Run notifications
//        (new NotificationController())->requestFormNotifications($requestForm, "REQUEST_FORM_PENDING");


//        $report = (new ReportController())->getCurrentReport();
//        $report->requestForms()->attach($requestForm);

            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new InventoryResource($inventory), 201);
            else {
                //Web Response
                return Redirect::back()->with('success', "{$inventory->name}: Inventories updated!");
            }
        }else {
            return Redirect::back()->with('error','Resource not found');
        }
    }
}
