<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'created_by' => $this->created_by,
            'assigned_to' => $this->assigned_to,
            'subscription_id' => $this->subscription_id,
            'legacy_pending_request_id' => $this->legacy_pending_request_id,
            'resolved_at' => optional($this->resolved_at)->toIso8601String(),
            'category' => new TicketCategoryResource($this->whenLoaded('category')),
            'messages' => TicketMessageResource::collection($this->whenLoaded('messages')),
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
