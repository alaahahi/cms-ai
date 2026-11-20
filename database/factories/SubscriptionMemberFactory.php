<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionMemberFactory extends Factory
{
    protected $model = SubscriptionMember::class;

    public function definition()
    {
        return [
            'subscription_id' => Subscription::factory(),
            'name' => $this->faker->name(),
            'relation' => 'self',
            'birthdate' => $this->faker->date(),
        ];
    }
}
