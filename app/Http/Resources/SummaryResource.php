<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => intval($this->id),
            "client" => $this->sale->client,
            "product" => [
                "id" => $this->product->id,
                "name" => $this->product->name,
                "inventories" => InventoryResource::collection($this->product->inventories),
            ],
            "isService" => $this->product->id == (new AppController())->SERVICES_PRODUCT_ID,
            "type" => 'HQ',
            "name" => $this->name,
            "quantified" => $this->quantified,
            "statusMessage" => $this->statusMessage,
            "variant" => $this->variant,
            "variantId" => intval($this->product_variant_id),
            "date" => intval($this->date),
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            "paymentStatus" => intval($this->getPaymentStatus($this->amount, $this->balance)),
            "quantity" => floatval($this->quantity),
            "description" => $this->description,
            "unitCost" => floatval($this->cost()),
            "units" => $this->units,
            "delivery" => $this->delivery != null && $this->delivery?->status != 0 ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "code" => (new AppController())->getZeroedNumber($this->delivery->code),
                "costs" => floatval($this->delivery->costs()),
                "location" => $this->delivery->location,
                "quantityDelivered" => floatval($this->delivery->quantity_delivered),
            ] : null,
            "overdue" => $this->delivery != null ? $this->delivery->overdue() : false,
            "status" => intval($this->status),
            "siteSaleSummary" => new SiteSaleSummaryResource($this->siteSaleSummary),
            "sale" => [
                "id" => intval($this->sale->id),
                "serial" => $this->sale->serial,
                'code' => "LL" . (new AppController())->getZeroedNumber($this->sale->code_alt),
                'client' => $this->sale->client,
                'date' => intval($this->sale->date),
            ],
            'profit' => floatval($this->profit()),
            'meta' => json_decode($this->meta),
            'waiver' => boolval($this->waiver),
            'deliveryRequests' => DeliveryRequestResource::collection($this->deliveryRequests),
        ];
    }

    public function getPaymentStatus($amount, $balance): int
    {
        //        dump($balance);
        if (isset($balance)) {
            if ($balance == $amount) {
                return 0;
            } elseif ($balance > 0 && $balance < $amount) {
                return 1;
            } elseif ($balance == 0) {
                return 2;
            }
        }
        return 3;
    }
}
