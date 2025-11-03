<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\ReceiptResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\SaleResource;
use App\Http\Resources\QuotationResource;
use App\Http\Resources\SiteSaleResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\SummaryResource;
use App\Http\Resources\SiteSaleSummaryResource;
use App\Http\Resources\DeliveryNoteResource;
use App\Models\Client;
use App\Models\DeliveryNote;
use App\Models\SiteSaleSummary;
use App\Models\Summary;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $client = Client::find($id);

        if (is_object($client)) {
            //check unpaid sales and deliveries
            $active_sales_raw = Summary::whereHas('sale.client', function ($query) use ($client) {
                $query->where('id', $client->id);
            })
                ->where('date', '>=', env('TIMESTAMP_CUTOFF'))
                ->where(function ($query) {
                    $query->where('balance', '>', 0)
                        ->orWhereHas('delivery', function ($deliveryQuery) {
                            $deliveryQuery->where('status', 1);
                        });
                });
            $active_sales_count = $active_sales_raw->count();
            $active_sales = $active_sales_raw->get();

            //check unpaid site sales and collections
            $active_site_sales_raw = SiteSaleSummary::whereHas('sale', function ($query) use ($client) {
                $query->where('client_id', $client->id)->where("date", ">=", env('TIMESTAMP_CUTOFF'));
            })->where(function ($query) {
                $query->where('balance', '>', 0)
                    ->orWhereColumn('quantity', '!=', 'collected');
            });
            // ->where('balance', '>', 0)
            // ->orWhereColumn('quantity', 'collected');

            $active_site_sales_count = $active_site_sales_raw->count();
            $active_site_sales = $active_site_sales_raw->get();

            $sales = $client->sales()->orderBy("date", "desc")->get();
            $receipts = $client->receipts()->orderBy("date", "desc")->get();
            $invoices = $client->invoices()->latest()->get();
            $quotations = $client->quotations()->latest()->get();
            $siteSales = $client->siteSales()->orderBy("date", "desc")->get();
            $collections = $client->collections()->orderBy("date", "desc")->get();
            $delivery_notes = DeliveryNote::whereHas('delivery.summary.sale', function ($query) use ($client) {
                $query->where('client_id', $client->id);
            })
                ->where("date", ">=", env('TIMESTAMP_CUTOFF'))
                ->orderBy("date", "desc");


            $total_payments = 0;
            $receipt_ids = [];
            foreach ($sales as $sale) {
                foreach ($sale->receipts as $receipt) {
                    $total_payments += $receipt->amount;
                    $receipt_ids[] = $receipt->id;
                }
            }
            foreach ($siteSales as $sale) {
                foreach ($sale->receipts as $receipt) {
                    //in case already paid
                    if (!in_array($receipt->id, $receipt_ids)) {
                        $total_payments += $receipt->amount;
                        $receipt_ids[] = $receipt->id;
                    }
                }
            }


            //Response
            return response()->json([
                "counts" => [
                    "active_sales" => $active_sales_count,
                    "active_site_sales" => $active_site_sales_count,
                    "sales" => $sales->count(),
                    "receipts" => $receipts->count(),
                    "invoices" => $invoices->count(),
                    "quotations" => $quotations->count(),
                    "siteSales" => $siteSales->count(),
                    "collections" => $collections->count(),
                    "delivery_notes" => $delivery_notes->count(),
                ],
                "total_payments" => $total_payments,

                'active_sales ' => SummaryResource::collection($active_sales),
                'active_site_sales ' => SiteSaleSummaryResource::collection($active_site_sales),
                'sales' => SaleResource::collection($sales->take((new AppController())->paginate)),
                'receipts' => ReceiptResource::collection($receipts->take((new AppController())->paginate)),
                'invoices' => InvoiceResource::collection($invoices->take((new AppController())->paginate)),
                'quotations' => QuotationResource::collection($quotations->take((new AppController())->paginate)),
                'siteSales' => SiteSaleResource::collection($siteSales->take((new AppController())->paginate)),
                'collections' => CollectionResource::collection($collections->take((new AppController())->paginate)),
                'delivery_notes' => DeliveryNoteResource::collection($delivery_notes->take((new AppController())->paginate)),

            ]);
        } else {
            return response()->json(['message' => "Client not found"], 404);
        }
    }
    public function getDetails(Request $request, $id)
    {

        $client = Client::find($id);

        if (is_object($client)) {
            switch ($request->query('section')) {
                case "SALES":
                    $sales = $client->sales()->orderBy("date", "desc")->paginate((new AppController())->paginate);
                    return response()->json(SaleResource::collection($sales));
                case "SITE_SALES":
                    $siteSales = $client->siteSales()->orderBy("date", "desc")->paginate((new AppController())->paginate);
                    return response()->json(SiteSaleResource::collection($siteSales));
                case "RECEIPTS":
                    $receipts = $client->receipts()->orderBy("date", "desc")->paginate((new AppController())->paginate);
                    return response()->json(ReceiptResource::collection($receipts));
                case "INVOICES":
                    $invoices = $client->invoices()->latest()->paginate((new AppController())->paginate);
                    return response()->json(InvoiceResource::collection($invoices));
                case "QUOTATIONS":
                    $quotations = $client->quotations()->latest()->paginate((new AppController())->paginate);
                    return response()->json(QuotationResource::collection($quotations));
                case "COLLECTIONS":
                    $collections = $client->collections()->orderBy("date", "desc")->paginate((new AppController())->paginate);
                    return response()->json(CollectionResource::collection($collections));
                case "DELIVERY_NOTES":
                    $delivery_notes = DeliveryNote::whereHas('delivery.summary.sale', function ($query) use ($client) {
                        $query->where('client_id', $client->id);
                    })
                        // ->where("date", ">=", env('TIMESTAMP_CUTOFF'))
                        ->orderBy("date", "desc")
                        ->paginate((new AppController())->paginate);
                    return response()->json(DeliveryNoteResource::collection($delivery_notes));
                default:
                    return response()->json([]);
            }
        } else {
            return response()->json(['message' => "Client not found"], 404);
        }
    }
}
