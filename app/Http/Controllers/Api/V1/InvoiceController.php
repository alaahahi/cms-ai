<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\PaymentResource;
use App\Http\Resources\V1\ProviderDueResource;
use App\Models\Invoice;
use App\Models\ProviderDue;
use App\Models\Subscription;
use App\Models\Visit;
use App\Services\Accounting\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends BaseApiController
{
    public function __construct(private InvoiceService $invoices)
    {
    }

    public function index(Request $request)
    {
        return $this->success(
            InvoiceResource::collection($this->invoices->listInvoices($request->get('status')))
        );
    }

    public function show(int $id)
    {
        $invoice = Invoice::with('payments')->find($id);
        if (! $invoice) {
            return $this->error('Invoice not found', [], 404);
        }

        return $this->success(new InvoiceResource($invoice));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subscription_id' => 'required|integer|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $subscription = Subscription::findOrFail($data['subscription_id']);
        $invoice = $this->invoices->createForSubscription(
            $subscription,
            (float) $data['amount'],
            $data['notes'] ?? null
        );

        return $this->success(new InvoiceResource($invoice), 'Invoice created', 201);
    }

    public function pay(Request $request, int $id)
    {
        $invoice = Invoice::find($id);
        if (! $invoice) {
            return $this->error('Invoice not found', [], 404);
        }

        $data = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $payment = $this->invoices->recordPayment(
            $invoice,
            $data,
            optional($request->user())->id
        );

        return $this->success(new PaymentResource($payment), 'Payment recorded', 201);
    }

    public function providerDues(Request $request)
    {
        $query = ProviderDue::query()->orderByDesc('id');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $this->success(ProviderDueResource::collection($query->limit(100)->get()));
    }

    public function createProviderDue(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|integer|exists:visits,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $visit = Visit::findOrFail($data['visit_id']);
        if (! $visit->provider_id) {
            return $this->error('Visit has no provider', [], 422);
        }

        $due = $this->invoices->createProviderDueFromVisit($visit, (float) $data['amount']);

        return $this->success(new ProviderDueResource($due), 'Provider due created', 201);
    }

    public function payProviderDue(int $id)
    {
        $due = ProviderDue::find($id);
        if (! $due) {
            return $this->error('Provider due not found', [], 404);
        }

        return $this->success(
            new ProviderDueResource($this->invoices->markProviderDuePaid($due)),
            'Provider due marked as paid'
        );
    }

    public function sync()
    {
        $count = $this->invoices->syncOrdersToInvoices();

        return $this->success(['synced' => $count], 'Invoices synced from orders');
    }
}
