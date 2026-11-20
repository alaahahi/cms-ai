<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'specialty_ar' => $this->specialty_ar,
            'specialty_en' => $this->specialty_en,
            'specialty' => $this->specialty,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'image' => $this->image,
            'review_rate' => $this->review_rate,
            'ex_year' => $this->ex_year,
            'is_active' => (bool) $this->is_active,
            'services' => ProviderServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
