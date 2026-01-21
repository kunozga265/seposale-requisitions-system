<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppController;
use App\Http\Resources\{
    SaleResource,
    SiteSaleResource,
    QuotationResource,
    InvoiceResource,
    ReceiptResource,
    CollectionResource,
    DeliveryNoteResource
};
use App\Models\Client;
use Illuminate\Http\JsonResponse;

class PortalController extends Controller
{
    /* -------------------------
     | Shared Helpers
     * -------------------------*/
    protected function clientOrFail(string $serial): Client|JsonResponse
    {
        $client = Client::where('serial', $serial)->first();

        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        return $client;
    }

    protected function listResponse($query, $resource, string $key)
    {
        return response()->json([
            'stats' => [
                'count' => $query->count(),
            ],
            $key => $resource::collection(
                $query->orderBy('date', 'desc')
                    ->take((new AppController())->paginate)
                    ->get()
            ),
        ]);
    }

    protected function singleResponse($query, $resource, string $message)
    {
        $item = $query->first();

        if (!$item) {
            return response()->json(['message' => $message], 404);
        }

        return response()->json(new $resource($item));
    }

    /* -------------------------
     | SALES
     * -------------------------*/
    public function sales($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        $all = [];

        foreach ($client->sales as $sale) {
            $all[] = new SaleResource($sale);
        }
        foreach ($client->siteSales as $sale) {
            $all[] = new SiteSaleResource($sale);
        }

        usort($all, function ($a, $b) {
            if ($a['date'] < $b['date']) {
                return 1;
            } elseif ($a['date'] > $b['date']) {
                return -1;
            }
            return 0;
        });

        return response()->json($all);
    }

    public function getSale($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->sales()->where('serial', $serial),
            SaleResource::class,
            'Sale not found'
        );
    }

    /* -------------------------
     | QUOTATIONS
     * -------------------------*/
    public function quotations($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->listResponse(
            $client->quotations(),
            QuotationResource::class,
            'quotations'
        );
    }

    public function getQuotation($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->quotations()->where('serial', $serial),
            QuotationResource::class,
            'Quotation not found'
        );
    }

    /* -------------------------
     | INVOICES
     * -------------------------*/
    public function invoices($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->listResponse(
            $client->invoices(),
            InvoiceResource::class,
            'invoices'
        );
    }

    public function getInvoice($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->invoices()->where('serial', $serial),
            InvoiceResource::class,
            'Invoice not found'
        );
    }

    /* -------------------------
     | RECEIPTS
     * -------------------------*/
    public function receipts($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->listResponse(
            $client->receipts(),
            ReceiptResource::class,
            'receipts'
        );
    }

    public function getReceipt($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->receipts()->where('serial', $serial),
            ReceiptResource::class,
            'Receipt not found'
        );
    }

    /* -------------------------
     | COLLECTIONS
     * -------------------------*/
    public function collections($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->listResponse(
            $client->collections(),
            CollectionResource::class,
            'collections'
        );
    }

    public function getCollection($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->collections()->where('serial', $serial),
            CollectionResource::class,
            'Collection not found'
        );
    }

    /* -------------------------
     | DELIVERY NOTES
     * -------------------------*/
    public function deliveryNotes($client_serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->listResponse(
            $client->deliveryNotes(),
            DeliveryNoteResource::class,
            'delivery_notes'
        );
    }

    public function getDeliveryNote($client_serial, $serial)
    {
        $client = $this->clientOrFail($client_serial);
        if ($client instanceof JsonResponse) return $client;

        return $this->singleResponse(
            $client->deliveryNotes()->where('serial', $serial),
            DeliveryNoteResource::class,
            'Delivery note not found'
        );
    }
}
