<?php

namespace Tests\Unit\Services;

use App\Models\PendingRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CustomerService\TicketService;
use Tests\BaselineTestCase;

class TicketServiceTest extends BaselineTestCase
{
    public function test_create_and_transition()
    {
        $user = User::factory()->create();
        $service = app(TicketService::class);

        $ticket = $service->create([
            'subject' => 'اختبار',
            'message' => 'مرحبا',
            'priority' => 'low',
        ], $user->id);

        $this->assertEquals(Ticket::STATUS_NEW, $ticket->status);
        $this->assertCount(1, $ticket->messages);

        $updated = $service->transition($ticket, Ticket::STATUS_CLOSED, $user->id);
        $this->assertEquals(Ticket::STATUS_CLOSED, $updated->status);
        $this->assertNotNull($updated->resolved_at);
    }

    public function test_sync_from_pending_requests()
    {
        $user = User::factory()->create();
        PendingRequest::create([
            'name' => 'Sync Me',
            'phone' => '07901234567',
            'user_id' => $user->id,
        ]);

        $count = app(TicketService::class)->syncFromPendingRequests();
        $this->assertSame(1, $count);
        $this->assertDatabaseHas('tickets', [
            'legacy_pending_request_id' => PendingRequest::first()->id,
        ]);
    }
}
