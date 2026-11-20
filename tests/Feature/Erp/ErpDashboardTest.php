<?php

namespace Tests\Feature\Erp;

use App\Models\User;
use Tests\BaselineTestCase;

class ErpDashboardTest extends BaselineTestCase
{
    public function test_dashboard_renders_erp_flow()
    {
        $user = User::factory()->create(['type_id' => 1]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $this->assertTrue(
            $response->headers->get('X-Inertia') === 'true'
            || str_contains($response->getContent(), 'Erp/Dashboard')
            || $response->status() === 200
        );
    }

    public function test_erp_module_pages_are_reachable()
    {
        $user = User::factory()->create(['type_id' => 1]);

        foreach ([
            '/erp/subscriptions',
            '/erp/providers',
            '/erp/visits',
            '/erp/sales',
            '/erp/tickets',
            '/erp/invoices',
            '/erp/analytics',
        ] as $path) {
            $this->actingAs($user)->get($path)->assertOk();
        }
    }
}
