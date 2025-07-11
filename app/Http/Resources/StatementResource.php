<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        // dd($this->groups());
        return 
        [
            "id"=>intval($this->id),
            "serial"=>$this->serial,
            "name"=>$this->name,
            "type"=>$this->type,
            "active"=>intval($this->active),
            "types"=>$this->groups(),
        ];
    }
}
