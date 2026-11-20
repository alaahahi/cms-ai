<?php

namespace Tests\Unit\Services;

use App\Models\Subscription;
use App\Models\Ticket;
use App\Services\Analytics\AnalyticsService;
use Tests\BaselineTestCase;

class AnalyticsServiceTest extends BaselineTestCase
{
    public function test_kpis_and_snapshot()
    {
        Subscription::factory()->create(['status' => Subscription::STATUS_ACTIVE]);
        Ticket::factory()->create(['status' => Ticket::STATUS_NEW]);

        $service = app(AnalyticsService::class);
        $kpis = $service->dashboardKpis();

        $this->assertGreaterThanOrEqual(1, $kpis['active_subscriptions']);
        $this->assertGreaterThanOrEqual(1, $kpis['open_tickets']);

        $captured = $service->captureSnapshot();
        $this->assertArrayHasKey('active_subscriptions', $captured);
        $this->assertDatabaseHas('analytics_snapshots', [
            'metric_key' => 'open_tickets',
        ]);
    }
}
