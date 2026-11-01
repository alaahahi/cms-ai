<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\CardService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for CardService Model
 */
class CardServiceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_working_days_accessor_returns_array()
    {
        $service = CardService::factory()->make([
            'working_days' => 'Saturday,Sunday,Monday',
        ]);

        $this->assertIsArray($service->working_days);
        $this->assertCount(3, $service->working_days);
        $this->assertContains('Saturday', $service->working_days);
    }

    public function test_working_hours_accessor_returns_array_with_start_and_end()
    {
        $service = CardService::factory()->make([
            'working_hours' => '09:00-17:00',
        ]);

        $this->assertIsArray($service->working_hours);
        $this->assertArrayHasKey('start', $service->working_hours);
        $this->assertArrayHasKey('end', $service->working_hours);
        $this->assertEquals('09:00', $service->working_hours['start']);
        $this->assertEquals('17:00', $service->working_hours['end']);
    }

    public function test_service_has_localized_name()
    {
        $service = CardService::factory()->make([
            'service_name_ar' => 'فحص أسنان',
            'service_name_en' => 'Dental Checkup',
        ]);

        app()->setLocale('ar');
        $this->assertEquals('فحص أسنان', $service->service_name);

        app()->setLocale('en');
        $this->assertEquals('Dental Checkup', $service->service_name);
    }

    public function test_is_popular_is_cast_to_boolean()
    {
        $service = CardService::factory()->make([
            'is_popular' => 1,
        ]);

        $this->assertIsBool($service->is_popular);
        $this->assertTrue($service->is_popular);
    }
}
