<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subscription_id' => $this->subscription_id,
            'legacy_profile_id' => $this->legacy_profile_id,
            'provider_id' => $this->provider_id,
            'provider_service_id' => $this->provider_service_id,
            'visit_type' => $this->visit_type,
            'recorded_by' => $this->recorded_by,
            'payload' => $this->payload,
            'visited_at' => optional($this->visited_at)->toIso8601String(),
            'provider' => new ProviderResource($this->whenLoaded('provider')),
        ];
    }
}
