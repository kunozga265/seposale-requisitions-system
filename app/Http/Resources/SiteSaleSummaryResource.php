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
            "id" => intval($this->id),
            "type" => 'OSS',
            "copy" => $this->summary != null,
            "name" => $this->name,
            "quantified" => $this->quantified,
            "statusMessage" => $this->statusMessage,
            "inventory" => $this->inventory,
            "inventoryStock" => floatval($this->inventory->stock()),
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            'paymentStatus' => intval($this->getPaymentStatus()),
            'collected' => floatval($this->collected),
            'collectionStatus' => $this->getCollectionStatus(),
            'quantity' => floatval($this->quantity),
            "collections" => $this->getCollections($this->collections),
            "site" => $this->sale->site,
            "trashed" => $this->deleted_at != null,
            'date' => intval($this->sale->date),
            "sale" => [
                "id" => intval($this->sale->id),
                "serial" => $this->sale->serial,
                "code" => "OSS" . (new AppController())->getZeroedNumber($this->sale->code),
                'client' => $this->sale->client,
                'date' => intval($this->sale->date),
            ],
            "status" => intval($this->status),
            "delivery" => $this->delivery != null ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "code" => (new AppController())->getZeroedNumber($this->delivery->code),
                "costs" => floatval($this->delivery->costs()),
            ] : null,
            "overdue" => $this->delivery != null ? $this->delivery->overdue() : false,
            'profit' => floatval($this->profit()),
            "unitCost" => floatval($this->cost()),
            'pendingPayments' => floatval($this->paidBalance() < 0 ? abs($this->paidBalance()) : 0),
        ];
    }


    public function getCollections($collections)
    {
        $array = [];
        foreach ($collections as $collection) {
            $by = $collection->collected_by != null ? ucwords($collection->collected_by) : " self";
            $phone_number = $collection->collected_by_phone_number != null ? "({$collection->collected_by_phone_number})" : "";
            $array[] = [
                "date" => intval($collection->date),
                "code" => $collection->code,
                "message" => "{$collection->quantity} collected by $by $phone_number",
                "photo" => $collection->photo,
            ];
        }

        return $array;
    }
}
