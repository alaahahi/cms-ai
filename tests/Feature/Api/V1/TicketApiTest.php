<?php

namespace Tests\Feature\Api\V1;

use App\Models\PendingRequest;
use App\Models\Ticket;
use App\Models\User;
use Tests\BaselineTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketApiTest extends BaselineTestCase
{
    public function test_create_list_message_and_transition_ticket()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/tickets', [
                'subject' => 'مشكلة تجديد',
                'description' => 'يحتاج مساعدة',
                'priority' => 'high',
                'message' => 'أول رسالة',
            ]);

        $create->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.subject', 'مشكلة تجديد')
            ->assertJsonPath('data.status', Ticket::STATUS_NEW);

        $id = $create->json('data.id');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/tickets')
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/tickets/'.$id.'/messages', [
                'message' => 'متابعة',
            ])
            ->assertCreated()
            ->assertJsonPath('data.message', 'متابعة');

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/tickets/'.$id.'/transition', [
                'status' => Ticket::STATUS_RESOLVED,
                'assigned_to' => $user->id,
            ])
            ->assertOk()
            ->assertJsonPath('data.status', Ticket::STATUS_RESOLVED)
            ->assertJsonPath('data.assigned_to', $user->id);
    }

    public function test_sync_tickets_from_pending_requests()
    {
        $user = User::factory()->create();
        PendingRequest::create([
            'name' => 'طلب تجريبي',
            'phone' => '07901110000',
            'address' => 'بغداد',
            'user_id' => $user->id,
            'family_members_names' => 'سارة',
        ]);

        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/tickets/sync')
            ->assertOk()
            ->assertJsonPath('data.synced', 1);

        $this->assertDatabaseHas('tickets', [
            'legacy_pending_request_id' => PendingRequest::first()->id,
        ]);
    }

    public function test_list_ticket_categories_ensures_defaults()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/ticket-categories')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name_ar', 'name_en'],
                ],
            ]);
    }
}
