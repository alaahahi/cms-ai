<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition()
    {
        return [
            'name_ar' => 'بطاقة ' . $this->faker->word(),
            'name_en' => 'Card ' . $this->faker->word(),
            'day' => $this->faker->numberBetween(30, 365),
            'price' => $this->faker->numberBetween(500000, 2000000),
            'currency' => 'IQD',
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
            'description_ar' => $this->faker->sentence(),
            'description_en' => $this->faker->sentence(),
            'image' => null,
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'expir_date' => now()->addMonths(6),
                'show_on_app' => true,
            ];
        });
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expir_date' => now()->subDays(1),
            ];
        });
    }
}
