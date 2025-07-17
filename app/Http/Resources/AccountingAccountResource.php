<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountingAccountResource extends JsonResource
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
            "code" => $this->code,
            "type" => $this->type,
            "special_type" => $this->special_type,
            "balance" => floatval($this->balance),
            "records" => AccountingRecordResource::collection($this->whenLoaded('records',$this->records()->orderBy('created_at', 'desc')->get())),
            "group" => new AccountsGroupLiteResource($this->whenLoaded('accountsGroup',$this->accountsGroup)),

        ];
    }
}
