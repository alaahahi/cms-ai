<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderFactory extends Factory
{
    protected $model = Provider::class;

    public function definition()
    {
        return [
            'name_ar' => 'عيادة '.$this->faker->word(),
            'name_en' => 'Clinic '.$this->faker->word(),
            'specialty_ar' => 'أسنان',
            'specialty_en' => 'Dental',
            'description_ar' => $this->faker->sentence(),
            'description_en' => $this->faker->sentence(),
            'review_rate' => 5,
            'ex_year' => 10,
            'is_active' => true,
        ];
    }
}
