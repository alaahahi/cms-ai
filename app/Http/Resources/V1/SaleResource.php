<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sales_rep_id' => $this->sales_rep_id,
            'subscription_id' => $this->subscription_id,
            'legacy_profile_id' => $this->legacy_profile_id,
            'subscription_type_id' => $this->subscription_type_id,
            'amount' => $this->amount,
            'cards_count' => $this->cards_count,
            'sale_date' => optional($this->sale_date)->format('Y-m-d'),
            'status' => $this->status,
            'notes' => $this->notes,
            'sales_rep' => new SalesRepResource($this->whenLoaded('salesRep')),
            'commission' => new CommissionResource($this->whenLoaded('commission')),
        ];
    }
}
