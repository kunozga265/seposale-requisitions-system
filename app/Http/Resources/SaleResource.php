<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'id' => $this->id,
            'code' => "LL".(new AppController())->getZeroedNumber($this->code_alt),
            'status' => intval($this->status),
            'client' => $this->client,
            'invoice' => $this->invoice != null ? [
                "id" => $this->invoice->id,
                'code' => (new AppController())->getZeroedNumber($this->invoice->code,$this->invoice->revision),
            ] : null,
            "expense" => new ExpenseResource($this->expense),
            'total' => floatval($this->total),
            'balance' => floatval($this->balance),
            'date' => $this->date,
            'createdDate' => $this->created_at->getTimestamp(),
            'location' => $this->location,
            'recipientName' => $this->recipient_name,
            'recipientProfession' => $this->recipient_profession,
            'recipientPhoneNumber' => $this->recipient_phone_number,
            'editable' => intval($this->editable),
            'comments' => json_decode($this->comments),
            'products' => SummaryResource::collection($this->products),
            'receipts' => ReceiptResource::collection($this->receipts),
            'generatedBy' => new UserResource($this->user),
            'delivery' => new DeliveryResource($this->delivery),

        ];
    }
}
