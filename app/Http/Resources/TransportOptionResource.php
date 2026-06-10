<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransportOptionResource extends JsonResource
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
            'product' => new ProductLiteResource($this->product),
            'vehicleType' => $this->vehicleType,
            'cost' => floatval($this->cost),
            'max' => floatval($this->max),
            'available' => boolval($this->available),
            'meta' => new TransportOptionMetaResource(json_decode($this->meta)),

        ];
    }
}
