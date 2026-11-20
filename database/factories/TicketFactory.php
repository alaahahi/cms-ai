<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'subject' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'priority' => 'medium',
            'status' => Ticket::STATUS_NEW,
            'category_id' => TicketCategory::factory(),
        ];
    }
}
