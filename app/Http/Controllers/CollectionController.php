<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteSaleSummaryResource;
use App\Models\Collection;
use App\Models\InventorySummary;
use App\Models\SiteSaleSummary;
use App\Models\SystemLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CollectionController extends Controller
{

    public function store(Request $request, $id)
    {

        $summary = SiteSaleSummary::find($id);

        //get user
        $user = (new AppController())->getAuthUser($request);


        if (is_object($summary)) {

            //Validate all the important attributes
            $request->validate([
                'quantity' => ['required'],
                'photo' => ['required'],
            ]);


            $collected_quantity = $summary->collected;
            $balance = $summary->quantity - $collected_quantity;
            if ($request->quantity == 0) {
                return Redirect::back()->with("error", "Please enter quantity collected");
            } else if ($balance < $request->quantity) {
                return Redirect::back()->with("error", "Quantity is more than what remains");
            } else {
                $balance -= $request->quantity;
                $collected_quantity += $request->quantity;
            }

            $inventorySummary = (new InventorySummaryController())->getSummary($summary->sale->site->id);

            $collection = Collection::create([
                'serial' =>  (new AppController())->generateUniqueCode("COLLECTION"),
                "code" => $this->getCodeNumber(),
                "client_id" => $summary->sale->client->id,
                "photo" => $request->photo,
                "collected_by" => $request->collected_by,
                "collected_by_phone_number" => $request->collected_by_phone_number,
                "inventory_id" => $summary->inventory->id,
                "inventory_summary_id" => $inventorySummary->id,
                "site_sale_summary_id" => $summary->id,
                "site_id" =>  $summary->sale->site->id,
                "quantity" => $request->quantity,
                "balance" => $balance,
                "user_id" => Auth::id(),
                "date"  => Carbon::now()->getTimestamp(),
            ]);

            $summary->update([
                "collected" => $collected_quantity,
            ]);

            //Update stock levels - Subtract from uncollected
            $uncollected_stock =  $collection->inventory->uncollected_stock - $collection->quantity;
            $collection->inventory->update([
               "uncollected_stock" =>   $uncollected_stock
            ]);



            $message = $summary->getCollectionStatus() == 2 ?
                "{$request->quantity} collected. Collection completed." :
                "{$request->quantity} collected. {$balance} is yet to be collected.";

            //Logging
            SystemLog::create([
                "user_id" => Auth::id(),
                "message" => $message,
                "collection_id" => $collection->id,
            ]);


            $summary->sale->update([
                "editable" => false
            ]);

            //send whatsapp notification
            (new NotificationController())->processWhatsappMessage("collection",$collection->serial, notify: $request->notify);


            if ((new AppController())->isApi($request))
                //API Response
                return response()->json(new SiteSaleSummaryResource($summary), 201);
            else {
                //Web Response
                return Redirect::route('sites.summaries.show',['code'=>$summary->sale->site->code,'id'=>$inventorySummary->id])->with('success', 'Collection recorded!');
            }
        } else {
            return Redirect::back()->with('error', 'Resource not found');
        }
    }

    public function trash(Request $request,$id)
    {
        $summary = SiteSaleSummary::find($id);

        if (is_object($summary)) {
            if ((new AppController())->isApi($request)) {
                //API Response
//                return response()->json(new SaleResource($summary));
            } else {

                $summary->delete();

                //Logging
                SystemLog::create([
                    "user_id" => Auth::id(),
                    "message" => "Collection has been cancelled",
                    "site_sale_id" => $summary->sale->id,
                ]);

                return Redirect::back()->with('success', 'Delivery cancelled successfully');
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Resource not found"], 404);
            } else {
                //Web Response
                return Redirect::back()->with('error', 'Resource not found');
            }
        }
    }

    private function getCodeNumber()
    {
        $last = Collection::orderBy("code", "desc")->first();
        if (is_object($last)) {
            return $last->code + 1;
        } else {
            return 1;
        }
    }

}
