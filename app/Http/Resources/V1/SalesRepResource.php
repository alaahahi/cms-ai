<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesRepResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'code' => $this->code,
            'commission_rate' => $this->commission_rate,
            'is_active' => (bool) $this->is_active,
            'user' => new UserResource($this->whenLoaded('user')),
            'targets' => TargetResource::collection($this->whenLoaded('targets')),
        ];
    }
}
