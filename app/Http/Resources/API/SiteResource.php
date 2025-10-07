<?php

namespace App\Http\Resources\API;

use App\Http\Resources\InventoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            "code" => $this->code,
            "location" => $this->location,
            "inventories" => InventoryResource::collection($this->inventories),
            "pendingCollections" => $this->pendingCollections(),
            // "accounts" => $this->accounts,
        ];
    }
}
