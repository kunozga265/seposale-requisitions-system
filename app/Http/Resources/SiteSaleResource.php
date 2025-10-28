<?php

namespace App\Http\Resources;

use App\Http\Controllers\AppController;
use App\Models\SiteSaleSummary;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSaleResource extends JsonResource
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
            'code' => (new AppController())->getZeroedNumber($this->code),
            'status' => intval($this->status),
            'client' => $this->client->toRawResource(),
            'total' => floatval($this->total),
            'balance' => floatval($this->balance),
            'date' => intval($this->date),
            'paymentMethod' => $this->paymentMethod,
            'reference' => $this->reference,
            'site' => [
                "id" => intval($this->site->id),
                "name" => $this->site->name,
                "code" => $this->site->code
            ],
            'editable' => $this->editable == 1,
            'products' => SiteSaleSummaryResource::collection($this->products()->withTrashed()->get()),
            'receipts' => ReceiptResource::collection($this->receipts),
            'generatedBy' => new UserResource($this->user),
            'profit' => floatval($this->profit()),
            'pendingPayments' => floatval($this->pendingPayments()),
        ];
    }
}
