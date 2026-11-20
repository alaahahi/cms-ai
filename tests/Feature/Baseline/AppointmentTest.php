<?php

namespace Tests\Feature\Baseline;

use Tests\BaselineTestCase;
use App\Models\Appointment;
use App\Models\User;
use App\Models\CardService;
use App\Models\Profile;

/**
 * Baseline tests for Appointment functionality
 */
class AppointmentTest extends BaselineTestCase
{
    public function test_appointment_can_be_created()
    {
        $user = User::factory()->create();
        $service = CardService::factory()->create();

        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
            'service_provider_id' => $service->id,
            'card_id' => 'CARD-123456',
        ]);

        $this->assertDatabaseHas('appointment', [
            'user_id' => $user->id,
            'card_id' => 'CARD-123456',
        ]);
    }

    public function test_appointment_belongs_to_user()
    {
        $user = User::factory()->create();
        $appointment = Appointment::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $appointment->user);
        $this->assertEquals($user->id, $appointment->user->id);
    }

    public function test_appointment_belongs_to_service_provider()
    {
        $service = CardService::factory()->create();
        $appointment = Appointment::factory()->create([
            'service_provider_id' => $service->id,
        ]);

        $this->assertInstanceOf(CardService::class, $appointment->serviceProvider);
        $this->assertEquals($service->id, $appointment->serviceProvider->id);
    }

    public function test_appointment_can_be_confirmed()
    {
        $appointment = Appointment::factory()->confirmed()->create();

        $this->assertEquals(2, $appointment->is_come);
    }

    public function test_appointment_can_be_cancelled()
    {
        $appointment = Appointment::factory()->create();
        $appointment->update(['is_come' => 0]);

        $this->assertEquals(0, $appointment->is_come);
    }
}
