<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'type_id' => $this->type_id,
            'roles' => $this->when(
                method_exists($this->resource, 'getRoleNames'),
                fn () => $this->getRoleNames()->values()
            ),
            'permissions' => $this->when(
                method_exists($this->resource, 'getAllPermissions'),
                fn () => $this->getAllPermissions()->pluck('name')->values()
            ),
        ];
    }
}
