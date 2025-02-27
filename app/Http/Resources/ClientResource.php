<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "address" => $this->address,
            "phoneNumber" => $this->phone_number != null ? "+{$this->phone_number}" : "",
            "phoneNumberOther" => $this->phone_number_other != null ? "+{$this->phone_number_other}" : "",
            "organisation" => intval($this->organisation),
            "alias" => $this->alias,
            "sales" => SaleResource::collection($this->sales),
//            "quotations" => QuotationResource::collection($this->sales),
        ];


    }
}
