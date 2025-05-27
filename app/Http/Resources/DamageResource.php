<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DamageResource extends JsonResource
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
            "batch" => $this->batch,
            "inventory" => $this->inventory,
            // "production_id" => $this->production,
            "quantity" => $this->quantity,
            "date" => $this->date,
            "cost" => $this->cost,
        ];
    }
}
