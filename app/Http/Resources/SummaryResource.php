<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
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
            "product" => $this->product,
            "variant" => $this->variant,
            "variantId" => intval($this->product_variant_id),
            "date" => $this->date,
            'amount' => floatval($this->amount),
            'balance' => floatval($this->balance),
            "paymentStatus" => intval($this->getPaymentStatus($this->amount, $this->balance)),
            "quantity" => $this->quantity,
            "description" => $this->description,
            "units" => $this->units,
            "delivery" => $this->delivery,
            "overdue" => $this->delivery != null ? $this->delivery->overdue() : false
        ];
    }

    private function getPaymentStatus($amount, $balance): int
    {
//        dump($balance);
        if (isset($balance)) {
            if ($balance == $amount) {
                return 0;
            } elseif ($balance > 0 && $balance < $amount) {
                return 1;
            } elseif ($balance == 0) {
                return 2;
            }
        }
        return 3;
    }
}
