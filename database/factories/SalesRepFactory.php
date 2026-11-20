<?php

namespace Database\Factories;

use App\Models\SalesRep;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesRepFactory extends Factory
{
    protected $model = SalesRep::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'commission_rate' => 10,
            'is_active' => true,
            'code' => 'SR-'.$this->faker->unique()->numerify('####'),
        ];
    }
}
