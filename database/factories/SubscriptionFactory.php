<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition()
    {
        return [
            'subscription_number' => 'SUB-'.$this->faker->unique()->numerify('######'),
            'subscription_type_id' => SubscriptionType::factory(),
            'subscriber_id' => User::factory(),
            'start_date' => now()->toDateString(),
            'end_date' => now()->addYear()->toDateString(),
            'status' => Subscription::STATUS_ACTIVE,
            'phone_number' => $this->faker->numerify('07#########'),
            'address' => $this->faker->address(),
            'invoice_number' => 'INV-'.$this->faker->numerify('####'),
            'source' => 'test',
            'legacy_profile_id' => null,
        ];
    }
}
