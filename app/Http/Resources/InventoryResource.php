<?php

namespace App\Http\Resources;

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
            "threshold" => floatval($this->threshold),
            "producible" => $this->producible == 1 ? true : false,
        ];
    }
}
