<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition()
    {
        return [
            'no' => $this->faker->unique()->randomNumber(5),
            'name' => $this->faker->name(),
            'birthdate' => $this->faker->date(),
            'certification' => null,
            'job' => $this->faker->jobTitle(),
            'address' => $this->faker->address(),
            'image' => null,
            'results' => 1, // default active
            'family_name' => null,
            'phone_number' => $this->faker->unique()->numerify('07#########'),
            'invoice_number' => $this->faker->unique()->numerify('INV-####'),
            'card_number' => $this->faker->unique()->numerify('CARD-######'),
            'card_id' => Card::factory(),
            'user_id' => User::factory(),
            'user_add' => null,
            'cardHolder_id' => function (array $attributes) {
                return $attributes['user_id'];
            },
            'user_doctor' => null,
            'user_accepted' => null,
            'created' => now()->format('Y-m-d'),
            'source' => 'web',
        ];
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'results' => 1,
            ];
        });
    }
}
