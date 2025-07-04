<?php

namespace App\Http\Resources;

use App\Models\Inventory;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
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
            "name" => $this->name,
            "units" => $this->units,
            "cost" => floatval($this->cost),
            "availableStock" => floatval($this->available_stock),
            "uncollectedStock" => floatval($this->uncollected_stock),
            "stock" => $this->stock(),
            "threshold" => floatval($this->threshold),
            "producible" => $this->producible == 1 ? true : false,
            "readyStock" => floatval($this->stock()),
            "product" => $this->product,
            "site" => $this->site,
            // "inventoryAccount" => $this->inventoryAccount
            "inventoryValue" => $this->value(),
            "cogsAccount" => $this->cogsAccount,
            "inventoryAccount" => $this->inventoryAccount,
            "revenueAccount" => $this->revenueAccount
        ];
    }
}
