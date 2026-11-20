<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class TargetResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sales_rep_id' => $this->sales_rep_id,
            'year' => $this->year,
            'month' => $this->month,
            'target_amount' => $this->target_amount,
            'target_cards' => $this->target_cards,
            'achieved_amount' => $this->achieved_amount,
            'achieved_cards' => $this->achieved_cards,
            'progress_percent' => $this->progressPercent(),
        ];
    }
}
