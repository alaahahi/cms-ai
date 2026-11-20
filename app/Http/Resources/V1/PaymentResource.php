<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'payment_method' => $this->payment_method,
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'notes' => $this->notes,
            'received_by' => $this->received_by,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'invoice' => new InvoiceResource($this->whenLoaded('invoice')),
        ];
    }
}
