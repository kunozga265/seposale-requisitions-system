<?php

namespace App\Http\Resources;

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
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "descriptionFull" => $this->description_full,
            "unit" => $this->unit,
            "quantity" => floatval($this->quantity),
            "cost" => floatval($this->cost),
            "costOriginal" => floatval($this->cost_original),
            "hasDiscount" => boolval($this->cost != $this->cost_original),
            "group" => $this->product,
            "photo" => $this->photo ?? $this->product->photo,
             "photos" => $this->photos ?? [$this->photo ?? $this->product->photo],
            "featured" => boolval($this->featured),
            "transportInclusive" => boolval($this->transport_inclusive),
            "specifications" => json_decode($this->specifications),
            "productInformation" => json_decode($this->product_information),
            "about" => $this->about,
        ];
    }
}
