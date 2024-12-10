<?php

namespace App\Http\Controllers;

use App\Http\Resources\CollectionResource;
use App\Http\Resources\InventoryResource;
use App\Http\Resources\InventorySummaryResource;
use App\Http\Resources\SiteSaleResource;
use App\Models\InventorySummary;
use App\Models\Site;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InventorySummaryController extends Controller
{
    public function show(Request $request, $code, $id)
    {
        $site = Site::where("code",$code)->first();

        if(is_object($site)){
            //
            $summary = $site->summaries()->where("id",$id)->first();

            if (is_object($summary)){
                return Inertia::render('InventorySummaries/Show', [
                    "site" => $site,
                    "summary" => new InventorySummaryResource($summary),
                    "inventories" => InventoryResource::collection($site->inventories),
                    "sales" => SiteSaleResource::collection($summary->sales),
                    "collections" => CollectionResource::collection($summary->collections),
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

    public function getSummary($site_id)
    {
        $site = Site::find($site_id);
        if(is_object($site)){
            //
            $last = $site->summaries()->orderBy("date","desc")->first();
            $today = Carbon::today()->getTimestamp();

            if (is_object($last)){
                //check if last summary is less than today's date
                if($last->date < $today){
                    $closing_stock = [];

                    foreach ($site->inventories as $inventory ){
                        $closing_stock[]=[
                            "id" => $inventory->id,
                            "name" => $inventory->name,
                            "availableStock" => $inventory->available_stock,
                            "uncollectedStock" => $inventory->uncollected_stock,
                        ];
                    }

                    $last->update([
                        "closing_stock" => json_encode($closing_stock),
                    ]);

                    return $this->create($site, $today);
                }else{
                    return $last;
                }
            }else{
                //create new summary for today
                return $this->create($site, $today);
            }

        }else{
            return null;
        }
    }

    private function create($site,$today)
    {
        $opening_stock = [];

        foreach ($site->inventories as $inventory ){
            $opening_stock[]=[
                "id" => $inventory->id,
                "name" => $inventory->name,
                "availableStock" => $inventory->available_stock,
                "uncollectedStock" => $inventory->uncollected_stock,
            ];
        }

        //create new summary for today
        return InventorySummary::create([
            "code" => $this->getCodeNumber(),
            "opening_stock" => json_encode($opening_stock),
            "closing_stock" => json_encode([]),
            "user_id" => Auth::id(),
            "site_id" => $site->id,
            "date" => $today,
        ]);
    }

    public function print(Request $request, $id)
    {
        //find out if the request is valid
        $summary = InventorySummary::find($id);

        if (is_object($summary)) {

            /*
                        $pdf=App::make('dompdf.wrapper');
                        $pdf->loadHTML('request');
                        return $pdf->stream('Request Form');*/

            $filename = "INVENTORY-SUMMARY#" . (new AppController())->getZeroedNumber($summary->code) . " - " . date('Ymd', $summary->date);

            $now_d = \Illuminate\Support\Carbon::createFromTimestamp($summary->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($summary->date, 'Africa/Lusaka')->format('H:i');




            $pdf = PDF::loadView('inventory-summary', [
                'code' => (new AppController())->getZeroedNumber($summary->code),
                'date' => $now_d,
                'time' => $now_t,
                'summary' => $summary,
            ]);
            return $pdf->download("$filename.pdf");

        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Sale not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Sale not found');
            }
        }
    }


    private function getCodeNumber()
    {
        $last = InventorySummary::orderBy("code","desc")->first();
        if (is_object($last)){
            return $last->code + 1;
        }else{
            return 1;
        }
    }
}
