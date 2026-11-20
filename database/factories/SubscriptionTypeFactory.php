<?php

namespace Database\Factories;

use App\Models\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionTypeFactory extends Factory
{
    protected $model = SubscriptionType::class;

    public function definition()
    {
        return [
            'name_ar' => 'باقة '.$this->faker->word(),
            'name_en' => 'Plan '.$this->faker->word(),
            'day' => 365,
            'price' => 850000,
            'currency' => 'IQD',
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
            'description_ar' => $this->faker->sentence(),
            'description_en' => $this->faker->sentence(),
            'legacy_card_id' => null,
        ];
    }
}
