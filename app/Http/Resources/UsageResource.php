<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class UsageResource extends JsonResource
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
            "production" => [
                "id" => $this->production?->id,
                "code" => (new AppController())->getZeroedNumber($this->production?->code)
            ],
            "date" => floatval($this->date),
            "quantity" => floatval($this->quantity),
            "cost" => $this->cost,
            "material" => $this->material,
        ];
    }
}
