<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subscription_number' => $this->subscription_number,
            'status' => $this->status,
            'start_date' => optional($this->start_date)->format('Y-m-d'),
            'end_date' => optional($this->end_date)->format('Y-m-d'),
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'invoice_number' => $this->invoice_number,
            'source' => $this->source,
            'legacy_profile_id' => $this->legacy_profile_id,
            'type' => new SubscriptionTypeResource($this->whenLoaded('type')),
            'members' => SubscriptionMemberResource::collection($this->whenLoaded('members')),
            'subscriber' => new UserResource($this->whenLoaded('subscriber')),
        ];
    }
}
