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
            "id" => $this->id,
            "client" => $this->sale->client,
            "product" => [
                "id" => $this->product,
                "name" => $this->product->name,
                "inventories" => InventoryResource::collection($this->product->inventories),
            ],
            "isService" => $this->product->id == (new AppController())->SERVICES_PRODUCT_ID,
            "variant" => $this->variant,
            "variantId" => intval($this->product_variant_id),
            "date" => $this->date,
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            "paymentStatus" => intval((new DeliveryController)->getPaymentStatus($this->amount, $this->balance)),
            "quantity" => $this->quantity,
            "description" => $this->description,
            "units" => $this->units,
            "delivery" => $this->delivery != null ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "expense" => new ExpenseResource($this->delivery->expense),
            ] : null,
            "overdue" => $this->delivery != null ? $this->delivery->overdue() : false,
            "status" => intval($this->status),
            "siteSaleSummary" => new SiteSaleSummaryResource($this->siteSaleSummary),
            "sale" => [
                 "id" => intval($this->sale->id),
                 'code' => "LL".(new AppController())->getZeroedNumber($this->sale->code_alt),
            ]
        ];
    }
}
