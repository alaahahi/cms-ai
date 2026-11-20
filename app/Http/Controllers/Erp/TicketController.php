<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StoreTicketRequest;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Services\CustomerService\TicketService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function __construct(private TicketService $tickets)
    {
    }

    public function index(Request $request)
    {
        $this->tickets->ensureDefaultCategories();

        return Inertia::render('Erp/Tickets/Index', [
            'tickets' => $this->tickets->list($request->get('status')),
            'categories' => TicketCategory::where('is_active', true)->orderBy('id')->get(),
            'filters' => [
                'status' => $request->get('status'),
            ],
        ]);
    }

    public function store(StoreTicketRequest $request)
    {
        $this->tickets->create($request->validated(), $request->user()->id);

        return redirect()
            ->route('erp.tickets.index')
            ->with('success', 'تم إنشاء التذكرة');
    }

    public function addMessage(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $data = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'nullable|boolean',
        ]);

        $this->tickets->addMessage(
            $ticket,
            $data['message'],
            $request->user()->id,
            (bool) ($data['is_internal'] ?? false)
        );

        return back()->with('success', 'تمت إضافة الرسالة');
    }

    public function transition(Request $request, int $id)
    {
        $ticket = Ticket::findOrFail($id);
        $data = $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $this->tickets->transition($ticket, $data['status'], $data['assigned_to'] ?? null);

        return back()->with('success', 'تم تحديث حالة التذكرة');
    }

    public function sync()
    {
        $count = $this->tickets->syncFromPendingRequests();

        return back()->with('success', "تمت مزامنة {$count} تذكرة من الطلبات المعلقة");
    }
}
