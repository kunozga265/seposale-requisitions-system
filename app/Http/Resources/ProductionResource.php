<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionResource extends JsonResource
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
            "code" => (new AppController())->getZeroedNumber($this->code),
            "date" => floatval($this->date),
            "openingStock" => json_decode($this->opening_stock),
            "closingStock" => json_decode($this->closing_stock),
            "user" => $this->user,
            "site" => $this->site,
            "usages" => UsageResource::collection($this->usages),
            "batches" => BatchResource::collection($this->batches),
        ];
    }
}
