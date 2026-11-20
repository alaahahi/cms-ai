<?php

namespace Database\Factories;

use App\Models\Transactions;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionsFactory extends Factory
{
    protected $model = Transactions::class;

    public function definition()
    {
        return [
            'wallet_id' => Wallet::factory(),
            'amount' => $this->faker->numberBetween(-100000, 100000),
            'type' => $this->faker->randomElement(['in', 'out', 'debt']),
            'morphed_id' => User::factory(),
            'morphed_type' => User::class,
            'description' => $this->faker->sentence(),
            'is_pay' => 0,
            'created' => now()->format('Y-m-d'),
            'card' => 0,
            'parent_id' => null,
            'card_id' => 1,
        ];
    }

    public function income()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'in',
                'amount' => abs($attributes['amount']),
            ];
        });
    }

    public function expense()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'out',
                'amount' => -abs($attributes['amount']),
                'is_pay' => 1,
            ];
        });
    }
}
