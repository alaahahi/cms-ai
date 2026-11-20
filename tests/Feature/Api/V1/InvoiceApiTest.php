<?php

namespace Tests\Feature\Api\V1;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Provider;
use App\Models\ProviderDue;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Visit;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class InvoiceApiTest extends BaselineTestCase
{
    public function test_create_invoice_and_record_payment()
    {
        $user = User::factory()->create();
        $subscription = Subscription::factory()->create([
            'subscriber_id' => $user->id,
            'status' => Subscription::STATUS_ACTIVE,
        ]);
        $token = JWTAuth::fromUser($user);

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/invoices', [
                'subscription_id' => $subscription->id,
                'amount' => 150000,
                'notes' => 'تجديد سنوي',
            ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.status', Invoice::STATUS_SENT);

        $id = $create->json('data.id');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/invoices/'.$id.'/pay', [
                'amount' => 150000,
                'payment_method' => 'cash',
            ])
            ->assertCreated()
            ->assertJsonPath('data.amount', '150000.00');

        $this->assertDatabaseHas('invoices', [
            'id' => $id,
            'status' => Invoice::STATUS_PAID,
        ]);
    }

    public function test_provider_due_lifecycle()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $visit = Visit::factory()->create(['provider_id' => $provider->id]);
        $token = JWTAuth::fromUser($user);

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/provider-dues', [
                'visit_id' => $visit->id,
                'amount' => 25000,
            ]);

        $create->assertCreated()
            ->assertJsonPath('data.status', ProviderDue::STATUS_PENDING);

        $dueId = $create->json('data.id');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/provider-dues/'.$dueId.'/pay')
            ->assertOk()
            ->assertJsonPath('data.status', ProviderDue::STATUS_PAID);
    }

    public function test_sync_invoices_from_orders()
    {
        Order::create([
            'order_id' => 'ORD-TEST-1',
            'amount' => 100000,
            'currency' => 'IQD',
            'status' => 'completed',
        ]);

        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/invoices/sync')
            ->assertOk()
            ->assertJsonPath('data.synced', 1);

        $this->assertDatabaseHas('invoices', [
            'legacy_order_id' => Order::first()->id,
            'status' => Invoice::STATUS_PAID,
        ]);
    }
}
