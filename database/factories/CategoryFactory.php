<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'card_id' => Card::factory(),
            'name_ar' => 'تصنيف ' . $this->faker->word(),
            'name_en' => 'Category ' . $this->faker->word(),
            'icon' => null,
            'color' => $this->faker->hexColor(),
            'discount' => 0,
            'parent_id' => null,
        ];
    }

    public function parent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => null,
            ];
        });
    }

    public function child()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Category::factory()->parent(),
            ];
        });
    }
}
