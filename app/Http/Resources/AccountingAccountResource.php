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
            "id" => $this->id,
            "name" => $this->name,
            "code" => $this->code,
            "type" => $this->type,
            "balance" => $this->balance,
            "records" => AccountingRecordResource::collection($this->whenLoaded('records',$this->records()->orderBy('date', 'desc')->get())),
            "group" => new AccountsGroupLiteResource($this->whenLoaded('accountsGroup',$this->accountsGroup)),

        ];
    }
}
