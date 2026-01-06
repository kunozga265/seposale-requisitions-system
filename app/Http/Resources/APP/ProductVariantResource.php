<?php

namespace App\Http\Resources\APP;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            "description" => $this->description,
            "unit" => $this->unit,
            "quantity" => floatval($this->quantity),
            "cost" => floatval($this->cost),
            "group" => $this->product,
            "name" => $this->name,
            "photo" => $this->photo,
        ];
    }
}
