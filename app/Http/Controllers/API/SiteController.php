<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\SiteSaleResource;
use App\Http\Resources\InventorySummaryResource;
use App\Models\Site;
use App\Models\SiteSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SiteController extends Controller
{
    public function sales(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            $sales = $site->sales()->orderBy("date", "desc")->paginate((new AppController())->paginate);
            return response()->json(SiteSaleResource::collection($sales));
        } else {
            return response()->json(['message' => "Site not found"], 404);
        }
    }

    public function reports(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            $summaries = $site->summaries()->orderBy("date", "desc")->paginate((new AppController())->paginate);
            return response()->json(InventorySummaryResource::collection($summaries));
        } else {
            return response()->json(['message' => "Site not found"], 404);
        }
    }

    public function collections(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();

        if (is_object($site)) {
            $collections = $site->collections()->orderBy("date", "desc")->paginate((new AppController())->paginate);
            return response()->json(CollectionResource::collection($collections));
        } else {
            return response()->json(['message' => "Site not found"], 404);
        }
    }

    public function printReport(Request $request, $code)
    {
        $site = Site::where("code", $code)->first();
        if (is_object($site)) {
            //
            $summary = $site->summaries()->orderBy("date", "desc")->first();

            $filename = "DAILY-REPORT#" . (new AppController())->getZeroedNumber($summary->code) . " - " . date('Ymd', $summary->date);

            $now_d = \Illuminate\Support\Carbon::createFromTimestamp($summary->date, 'Africa/Lusaka')->format('F j, Y');
            $now_t = Carbon::createFromTimestamp($summary->date, 'Africa/Lusaka')->format('H:i');

            $sum = 0;
            foreach ($summary->sales as $sale) {
                if ($sale->paymentMethod != null)
                    if ($sale->paymentMethod->id == 1) {
                        $sum += $sale->total;
                    }
            }

            $pdf = PDF::loadView('inventory-summary', [
                'code' => (new AppController())->getZeroedNumber($summary->code),
                'date' => $now_d,
                'time' => $now_t,
                'summary' => $summary,
                'sum' => $sum,
            ]);
            return $pdf->download("$filename.pdf");
        } else {
            return response()->json(['message' => "Sale not found"], 404);
        }
    }
}
