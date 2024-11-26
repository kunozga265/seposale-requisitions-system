<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
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

        $collections = [];
        foreach ($this->collections as $collection){
            $by = $collection->collected_by != null ? $collection->collected_by : " self";
            $phone_number = $collection->collected_by_phone_number != null ? "({$collection->collected_by_phone_number})" : "";
            $collections[] = [
                "date" => intval($collection->date),
                "message" => "{$collection->quantity} collected by $by $phone_number",
            ];
        }


        return [
            "id" => $this->id,
            "inventory" => $this->inventory,
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            'paymentStatus' => $this->getPaymentStatus(),
            'collected' => floatval($this->collected),
            'collectionStatus' => $this->getCollectionStatus(),
            'quantity' => floatval($this->quantity),
            "collections" =>$collections,
            "site" => $this->site,
            "sale" => [
                "id" => $this->sale->id,
                "code" => (new AppController())->getZeroedNumber($this->sale->code),
            ],
        ];
    }
}
