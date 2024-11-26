<?php

namespace App\Http\Controllers;

use App\Http\Resources\SiteResource;
use App\Http\Resources\SiteSaleSummaryResource;
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
                $summaries = SiteSaleSummary::latest()->get();

                $filtered = [];


                foreach ($summaries as $summary){
                    if($summary->getPaymentStatus() < 2){
                        $filtered [] = [
                            "id" => $summary->id,
                            "inventory" => $summary->inventory,
                            'amount' => floatval($summary->amount),
                            'balance' => floatval($summary->balance),
                            'paymentStatus' => $summary->getPaymentStatus(),
                            'collected' => floatval($summary->collected),
                            'collectionStatus' => $summary->getCollectionStatus(),
                            'quantity' => floatval($summary->quantity),
                            "collections" =>(new SiteSaleSummaryController())->getCollections($summary->collections),
                            "site" => $summary->site,
                            "trashed" => $summary->deleted_at != null,
                            "sale" => [
                                "id" => $summary->sale->id,
                                "code" => (new AppController())->getZeroedNumber($summary->sale->code),
                                'client' => $summary->sale->client,
                            ],
                        ];
                    }
                }


                //Web Response
                return Inertia::render('Sites/Overview', [
                    'site' => new SiteResource($site),
                    'collections' => $filtered,
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
