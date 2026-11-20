<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CardServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'card_id' => $this->card_id,
            'category_id' => $this->category_id,
            'service_name_ar' => $this->service_name_ar,
            'service_name_en' => $this->service_name_en,
            'service_name' => $this->service_name,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'description' => $this->description,
            'specialty_ar' => $this->specialty_ar,
            'specialty_en' => $this->specialty_en,
            'specialty' => $this->specialty,
            'price' => $this->price,
            'currency' => $this->currency,
            'working_days' => $this->working_days,
            'working_hours' => $this->working_hours,
            'appointments_per_day' => $this->appointments_per_day,
            'expir_date' => optional($this->expir_date)->format('Y-m-d'),
            'show_on_app' => (bool) ($this->show_on_app ?? true),
            'is_popular' => (bool) $this->is_popular,
            'review_rate' => $this->review_rate,
            'ex_year' => $this->ex_year,
            'image' => $this->image,
            'card' => $this->whenLoaded('card', fn () => new CardResource($this->card)),
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name_ar' => $this->category->name_ar,
                    'name_en' => $this->category->name_en,
                ];
            }),
        ];
    }
}
