<?php

namespace Database\Factories;

use App\Models\CardService;
use App\Models\Card;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardServiceFactory extends Factory
{
    protected $model = CardService::class;

    public function definition()
    {
        return [
            'card_id' => Card::factory(),
            'category_id' => Category::factory(),
            'service_name_ar' => 'خدمة ' . $this->faker->word(),
            'service_name_en' => 'Service ' . $this->faker->word(),
            'description_ar' => $this->faker->sentence(),
            'description_en' => $this->faker->sentence(),
            'specialty_ar' => $this->faker->word(),
            'specialty_en' => $this->faker->word(),
            'price' => $this->faker->numberBetween(25000, 150000),
            'currency' => 'IQD',
            'working_days' => 'Saturday,Sunday,Monday,Tuesday,Wednesday',
            'working_hours' => '09:00-17:00',
            'appointments_per_day' => 10,
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
            'is_popular' => false,
            'review_rate' => 5,
            'ex_year' => 0,
            'image' => null,
        ];
    }

    public function popular()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_popular' => true,
            ];
        });
    }
}
