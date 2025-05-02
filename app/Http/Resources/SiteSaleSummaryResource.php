<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use App\Http\Controllers\SiteSaleSummaryController;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSaleSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {




        return [
            "id" => $this->id,
            "inventory" => $this->inventory,
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            'paymentStatus' => $this->getPaymentStatus(),
            'collected' => floatval($this->collected),
            'collectionStatus' => $this->getCollectionStatus(),
            'quantity' => floatval($this->quantity),
            "collections" =>(new SiteSaleSummaryController())->getCollections($this->collections),
            "site" => $this->sale->site,
            "trashed" => $this->deleted_at != null,
            "sale" => [
                "id" => $this->sale->id,
                "code" => (new AppController())->getZeroedNumber($this->sale->code),
            ],
            "status" => intval($this->status),
            "delivery" => $this->delivery != null ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
            ] : null,
            "overdue" => $this->delivery != null ? $this->delivery->overdue() : false,
        ];
    }
}
