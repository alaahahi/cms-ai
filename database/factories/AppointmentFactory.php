<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use App\Models\CardService;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('now', '+30 days');
        $end = (clone $start)->modify('+1 hour');

        return [
            'card_id' => $this->faker->numerify('CARD-######'),
            'user_id' => User::factory(),
            'service_provider_id' => CardService::factory(),
            'start' => $start,
            'end' => $end,
            'note' => $this->faker->sentence(),
            'is_come' => 0,
            'source' => 'web',
        ];
    }

    public function confirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_come' => 2,
            ];
        });
    }
}
