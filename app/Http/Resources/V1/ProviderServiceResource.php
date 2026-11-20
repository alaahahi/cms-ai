<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'provider_id' => $this->provider_id,
            'service_category_id' => $this->service_category_id,
            'subscription_type_id' => $this->subscription_type_id,
            'price' => $this->price,
            'currency' => $this->currency,
            'working_days' => $this->working_days,
            'working_hours' => $this->working_hours,
            'appointments_per_day' => $this->appointments_per_day,
            'expir_date' => optional($this->expir_date)->format('Y-m-d'),
            'show_on_app' => (bool) $this->show_on_app,
            'is_popular' => (bool) $this->is_popular,
            'provider' => new ProviderResource($this->whenLoaded('provider')),
        ];
    }
}
