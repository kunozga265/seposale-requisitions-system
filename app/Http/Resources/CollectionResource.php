<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
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
            'serial' => $this->serial,
            "code" => (new AppController())->getZeroedNumber($this->code),
            "client" => $this->client,
            "photo" => $this->photo,
            "collectedBy" => $this->collected_by,
            "collectedByPhoneNumber" => $this->collected_by_phone_number,
            "inventory" => $this->inventory,
            "inventorySummary" => $this->inventorySummary,
            "siteSaleSummary" => new SiteSaleSummaryResource($this->siteSaleSummary),
            "site" => $this->site,
            "quantity" => floatval($this->quantity),
            "balance" => floatval($this->balance),
            "user" => $this->user,
            "date" => intval($this->date),
            "message" => "Collected {$this->quantity} by " . isset($this->collected_by) ? $this->collected_by : " self",
            'whatsapp' => $this->whatsapp != null ? intval($this->whatsapp) : false,
        ];
    }
}
