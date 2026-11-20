<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'order_id' => $this->faker->uuid(),
            'amount' => $this->faker->numberBetween(500000, 2000000),
            'currency' => 'IQD',
            'status' => 'pending',
            'state' => 'initial',
            'link' => $this->faker->url(),
            'token' => $this->faker->uuid(),
            'transactionId' => $this->faker->uuid(),
            '3DSecureId' => $this->faker->uuid(),
            'card_number' => null,
            'salse' => 'بدون مندوب',
            'name' => $this->faker->name(),
            'family_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('07#########'),
            'address' => $this->faker->address(),
        ];
    }

    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'state' => 'success',
            ];
        });
    }
}
