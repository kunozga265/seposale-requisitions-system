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
            'id' => intval($this->id),
            'serial' => $this->serial,
            "type" => 'HQ',
            'code' => "LL" . (new AppController())->getZeroedNumber($this->code_alt),
            'status' => intval($this->status),
            'client' => $this->client->toRawResource(),
            'invoice' => $this->invoice != null ? [
                "id" => $this->invoice->id,
                'code' => (new AppController())->getZeroedNumber($this->invoice->code, $this->invoice->revision),
            ] : null,
            // "expense" => new ExpenseResource($this->expense),
            'collections' => $this->collections()->orderBy('date', 'asc')->get(),
            'vat' => floatval($this->vat),
            'total' => floatval($this->total),
            'totalRaw' => floatval($this->total - $this->vat),
            'balance' => floatval($this->balance),
            'date' => intval($this->date),
            'createdDate' => intval($this->created_at->getTimestamp()),
            'location' => $this->location,
            'recipientName' => $this->recipient_name,
            'recipientProfession' => $this->recipient_profession,
            'recipientPhoneNumber' => $this->recipient_phone_number,
            'localPurchaseOrder' => $this->local_purchase_order,
            'editable' => intval($this->editable) == 1,
            'comments' => json_decode($this->comments),
            'products' => SummaryResource::collection($this->products),
            'receipts' => ReceiptResource::collection($this->receipts),
            'pops' => PaymentReceiptResource::collection($this->pops),
            'generatedBy' => new UserResource($this->user),
            'delivery' => new DeliveryResource($this->delivery),
            'whatsapp' => $this->whatsapp != null ? intval($this->whatsapp) : false,
            'profit' => $this->profit(),
            "notes" => DeliveryNoteResource::collection($this->deliveryNotes()->get()),
            'clientGenerated' => boolval($this->client_generated),
            'confirmed' => boolval($this->confirmed),
            'confirmedDate' => floatval($this->confirmed_date),
            'meta' => json_decode($this->meta),
            'zone' => new ZoneResource($this->zone),
             "deliveryRequests" => DeliveryRequestResource::collection($this->deliveryRequests),
            'agents' => $this->agents->map(fn ($agent) => [
                'clientId' => $agent->client_id,
                'name' => $agent->client->getName(),
                'percentage' => floatval($agent->percentage),
            ])->values(),

        ];
    }
}
