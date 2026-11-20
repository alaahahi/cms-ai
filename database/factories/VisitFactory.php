<?php

namespace Database\Factories;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitFactory extends Factory
{
    protected $model = Visit::class;

    public function definition()
    {
        return [
            'visit_type' => Visit::TYPE_LAB,
            'payload' => ['note' => 'test'],
            'visited_at' => now(),
        ];
    }
}
