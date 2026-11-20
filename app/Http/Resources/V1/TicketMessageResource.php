<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ticket_id' => $this->ticket_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'is_internal' => $this->is_internal,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
