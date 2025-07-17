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
            "id" => intval($this->id),
            "date" => intval($this->date),
            "price" => floatval($this->price),
            "quantity" => floatval($this->quantity),
            "balance" => floatval($this->balance),
            "comments" => $this->comments,
            "photo" => $this->photo,
            "readyDate" => intval($this->ready_date),
            "inventory" => $this->inventory,
            "user" => new UserResource($this->user),
        ];
    }
}
