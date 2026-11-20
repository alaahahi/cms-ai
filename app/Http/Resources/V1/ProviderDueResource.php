<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderDueResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'provider_id' => $this->provider_id,
            'visit_id' => $this->visit_id,
            'amount' => $this->amount,
            'period_start' => optional($this->period_start)->format('Y-m-d'),
            'period_end' => optional($this->period_end)->format('Y-m-d'),
            'status' => $this->status,
            'paid_at' => optional($this->paid_at)->toIso8601String(),
        ];
    }
}
