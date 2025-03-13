<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            "date" => $this->date,
            "price" => $this->price,
            "quantity" => $this->quantity,
            "balance" => $this->balance,
            "comments" => $this->comments,
            "photo" => $this->photo,
            "inventory" => $this->inventory,
            "user" => new UserResource($this->user),
        ];
    }
}
