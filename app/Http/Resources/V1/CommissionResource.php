<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sales_rep_id' => $this->sales_rep_id,
            'sale_id' => $this->sale_id,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'status' => $this->status,
            'paid_at' => optional($this->paid_at)->toIso8601String(),
        ];
    }
}
