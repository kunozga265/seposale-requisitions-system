<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class WhatsappMessageResource extends JsonResource
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
            "status" => $this->status,
            "name" => $this->name,
            "date" => $this->created_at->getTimestamp(),
            "phoneNumber" => $this->phone_number,
            "type" => $this->type == 1, //client
            "messageType" => $this->message_type,
            // "message" => $this->message,
            "wamid" => $this->wamid,
            "payload" => json_decode($this->payload),
            "client" => new ClientResource($this->client),
            "quotation" => new QuotationResource($this->quotation),
            "invoice" => new InvoiceResource($this->invoice),
            "receipt" => new ReceiptResource($this->receipt),
            'sale' => $this->sale != null ? [
                'id' => $this->sale->id,
                'serial' => $this->sale->serial,
                'code' => (new AppController())->getZeroedNumber($this->sale->code_alt),
            ] : null,
            "delivery" => $this->delivery != null && $this->delivery?->status != 0 ? [
                "id" => intval($this->delivery->id),
                "status" => intval($this->delivery->status),
                "code" => (new AppController())->getZeroedNumber($this->delivery->code),
                "costs" => floatval($this->delivery->costs()),
            ] : null,
            "collection" => new CollectionResource($this->collection),
            "creditVoucher" => new CreditVoucherResource($this->creditVoucher),
            "supplierVoucher" => new SupplierVoucherResource($this->supplierVoucher),
            "requestFormItem" => new RequestFormItemResource($this->requestFormItem),
            "user" => new UserResource($this->user),
            "statuses" => WhatsappMessageStatusResource::collection($this->statuses),
        ];
    }
}
