<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\ProviderDue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderDueFactory extends Factory
{
    protected $model = ProviderDue::class;

    public function definition()
    {
        return [
            'provider_id' => Provider::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'period_start' => now()->startOfMonth()->toDateString(),
            'period_end' => now()->endOfMonth()->toDateString(),
            'status' => ProviderDue::STATUS_PENDING,
        ];
    }
}
