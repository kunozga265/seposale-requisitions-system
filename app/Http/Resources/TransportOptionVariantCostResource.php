<?php

namespace App\Http\Resources;

use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportOptionVariantCostResource extends JsonResource
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
            'id' => intval($this->id),
            'max' => floatval($this->max),
            'product' => new ProductVariantResource(ProductVariant::find($this->id))
        ];
    }
}
