<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'no' => $this->no,
            'name' => $this->name,
            'birthdate' => $this->birthdate,
            'job' => $this->job,
            'address' => $this->address,
            'phone_number' => $this->phone_number,
            'invoice_number' => $this->invoice_number,
            'card_number' => $this->card_number,
            'card_id' => $this->card_id,
            'user_id' => $this->user_id,
            'cardHolder_id' => $this->cardHolder_id,
            'results' => $this->results,
            'family_name' => $this->family_name,
            'source' => $this->source,
            'created' => $this->created,
            'image' => $this->image,
            'card' => $this->whenLoaded('card', fn () => new CardResource($this->card)),
        ];
    }
}
