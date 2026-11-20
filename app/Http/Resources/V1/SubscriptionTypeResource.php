<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionTypeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'day' => $this->day,
            'price' => $this->price,
            'currency' => $this->currency,
            'expir_date' => optional($this->expir_date)->format('Y-m-d'),
            'show_on_app' => (bool) $this->show_on_app,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'image' => $this->image,
            'legacy_card_id' => $this->legacy_card_id,
        ];
    }
}
