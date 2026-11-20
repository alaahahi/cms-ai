<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\TicketCategoryResource;
use App\Http\Resources\V1\TicketMessageResource;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Services\CustomerService\TicketService;
use Illuminate\Http\Request;

class TicketController extends BaseApiController
{
    public function __construct(private TicketService $tickets)
    {
    }

    public function categories()
    {
        $this->tickets->ensureDefaultCategories();

        return $this->success(
            TicketCategoryResource::collection(
                TicketCategory::where('is_active', true)->orderBy('id')->get()
            )
        );
    }

    public function index(Request $request)
    {
        return $this->success(
            TicketResource::collection($this->tickets->list($request->get('status')))
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'category_id' => 'nullable|integer|exists:ticket_categories,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'message' => 'nullable|string',
        ]);

        $ticket = $this->tickets->create($data, optional($request->user())->id);

        return $this->success(new TicketResource($ticket), 'Ticket created', 201);
    }

    public function show(int $id)
    {
        $ticket = Ticket::with(['category', 'messages', 'creator', 'assignee'])->find($id);
        if (! $ticket) {
            return $this->error('Ticket not found', [], 404);
        }

        return $this->success(new TicketResource($ticket));
    }

    public function addMessage(Request $request, int $id)
    {
        $ticket = Ticket::find($id);
        if (! $ticket) {
            return $this->error('Ticket not found', [], 404);
        }

        $data = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'nullable|boolean',
        ]);

        $message = $this->tickets->addMessage(
            $ticket,
            $data['message'],
            optional($request->user())->id,
            (bool) ($data['is_internal'] ?? false)
        );

        return $this->success(new TicketMessageResource($message), 'Message added', 201);
    }

    public function transition(Request $request, int $id)
    {
        $ticket = Ticket::find($id);
        if (! $ticket) {
            return $this->error('Ticket not found', [], 404);
        }

        $data = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $updated = $this->tickets->transition(
            $ticket,
            $data['status'],
            $data['assigned_to'] ?? null
        );

        return $this->success(new TicketResource($updated), 'Ticket updated');
    }

    public function sync()
    {
        $count = $this->tickets->syncFromPendingRequests();

        return $this->success(['synced' => $count], 'Tickets synced from pending requests');
    }
}
