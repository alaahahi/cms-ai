<?php

namespace App\Services\CustomerService;

use App\Models\PendingRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class TicketService extends BaseService
{
    public function ensureDefaultCategories(): void
    {
        TicketCategory::firstOrCreate(
            ['name_en' => 'New Card Request'],
            ['name_ar' => 'طلب بطاقة جديدة', 'description' => 'Legacy pending requests', 'is_active' => true]
        );
        TicketCategory::firstOrCreate(
            ['name_en' => 'General Inquiry'],
            ['name_ar' => 'استفسار عام', 'is_active' => true]
        );
    }

    public function syncFromPendingRequests(): int
    {
        $this->ensureDefaultCategories();
        $categoryId = TicketCategory::where('name_en', 'New Card Request')->value('id');
        $count = 0;

        PendingRequest::query()->orderBy('id')->chunkById(100, function ($rows) use (&$count, $categoryId) {
            foreach ($rows as $request) {
                $ticket = Ticket::updateOrCreate(
                    ['legacy_pending_request_id' => $request->id],
                    [
                        'subject' => 'طلب بطاقة جديدة - '.($request->name ?? $request->phone),
                        'description' => trim(($request->address ?? '').' / members: '.($request->family_members_names ?? '')),
                        'priority' => 'medium',
                        'status' => Ticket::STATUS_NEW,
                        'category_id' => $categoryId,
                        'created_by' => $request->user_id,
                    ]
                );

                if ($ticket->messages()->count() === 0) {
                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $request->user_id,
                        'message' => 'Synced from pending request #'.$request->id,
                        'is_internal' => true,
                    ]);
                }
                $count++;
            }
        });

        return $count;
    }

    public function create(array $data, ?int $createdBy = null): Ticket
    {
        return $this->transaction(function () use ($data, $createdBy) {
            $ticket = Ticket::create([
                'subject' => $data['subject'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'] ?? 'medium',
                'status' => Ticket::STATUS_NEW,
                'category_id' => $data['category_id'] ?? null,
                'created_by' => $createdBy,
                'assigned_to' => $data['assigned_to'] ?? null,
                'subscription_id' => $data['subscription_id'] ?? null,
            ]);

            if (! empty($data['message'])) {
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $createdBy,
                    'message' => $data['message'],
                    'is_internal' => false,
                ]);
            }

            return $ticket->fresh(['messages', 'category']);
        });
    }

    public function addMessage(Ticket $ticket, string $message, ?int $userId = null, bool $internal = false): TicketMessage
    {
        return TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'message' => $message,
            'is_internal' => $internal,
        ]);
    }

    public function transition(Ticket $ticket, string $status, ?int $assignedTo = null): Ticket
    {
        $ticket->status = $status;
        if ($assignedTo) {
            $ticket->assigned_to = $assignedTo;
        }
        if (in_array($status, [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED], true)) {
            $ticket->resolved_at = now();
        }
        $ticket->save();

        return $ticket->fresh();
    }

    public function list(?string $status = null): Collection
    {
        $query = Ticket::with(['category', 'creator', 'assignee'])->orderByDesc('id');
        if ($status) {
            $query->where('status', $status);
        }

        return $query->limit(100)->get();
    }
}
