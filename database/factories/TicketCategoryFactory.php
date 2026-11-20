<?php

namespace Database\Factories;

use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketCategoryFactory extends Factory
{
    protected $model = TicketCategory::class;

    public function definition()
    {
        return [
            'name_ar' => 'فئة '.$this->faker->unique()->word(),
            'name_en' => 'Category '.$this->faker->unique()->word(),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
