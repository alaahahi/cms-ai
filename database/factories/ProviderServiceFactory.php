<?php

namespace Database\Factories;

use App\Models\Provider;
use App\Models\ProviderService;
use App\Models\SubscriptionType;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderServiceFactory extends Factory
{
    protected $model = ProviderService::class;

    public function definition()
    {
        return [
            'provider_id' => Provider::factory(),
            'subscription_type_id' => SubscriptionType::factory(),
            'price' => 50000,
            'currency' => 'IQD',
            'working_days' => 'Saturday,Sunday,Monday',
            'working_hours' => '09:00-17:00',
            'appointments_per_day' => 10,
            'expir_date' => now()->addYear(),
            'show_on_app' => true,
            'is_popular' => false,
        ];
    }
}
