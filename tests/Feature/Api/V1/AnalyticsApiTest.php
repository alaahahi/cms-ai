<?php

namespace Tests\Feature\Api\V1;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\User;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnalyticsApiTest extends BaselineTestCase
{
    public function test_dashboard_and_snapshot()
    {
        Subscription::factory()->create(['status' => Subscription::STATUS_ACTIVE]);
        Ticket::factory()->create(['status' => Ticket::STATUS_NEW]);
        Invoice::factory()->create([
            'status' => Invoice::STATUS_SENT,
            'due_date' => now()->subDay()->toDateString(),
            'amount' => 50000,
        ]);

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/analytics/dashboard')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    'kpis' => [
                        'active_subscriptions',
                        'open_tickets',
                        'unpaid_invoices',
                    ],
                    'cost_analysis',
                    'alerts',
                ],
            ]);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/analytics/snapshot')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('analytics_snapshots', [
            'metric_key' => 'active_subscriptions',
        ]);
    }
}
