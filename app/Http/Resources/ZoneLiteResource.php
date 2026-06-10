<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ZoneLiteResource extends JsonResource
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
            "id" => intval($this->id),
            "name" => $this->name,
            "level" =>  intval($this->level),
            "cost" => floatval($this->cost),
            // "costs" =>  ZoneCostResource::collection(json_decode($this->costs)),
            // "coordinates" => json_decode($this->coordinates),
            "options" => $this->transportOptions->count()
        ];
    }
}
