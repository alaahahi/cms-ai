<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'subscription_id' => $this->subscription_id,
            'legacy_order_id' => $this->legacy_order_id,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'due_date' => optional($this->due_date)->format('Y-m-d'),
            'status' => $this->status,
            'paid_at' => optional($this->paid_at)->toIso8601String(),
            'notes' => $this->notes,
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
        ];
    }
}
