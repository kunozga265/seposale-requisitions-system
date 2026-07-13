<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "name" => $this->name,
            "slug" => $this->slug,
            "photo" => $this->photo,
            "description" => $this->description,
            "descriptionFull" => $this->description_full,
            "variants" => ProductVariantResource::collection($this->variants),
            "inventories" => InventoryResource::collection($this->inventories),
        ];
    }
}
