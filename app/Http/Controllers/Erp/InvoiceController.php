<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StoreInvoiceRequest;
use App\Models\Invoice;
use App\Models\ProviderDue;
use App\Models\Subscription;
use App\Models\Visit;
use App\Services\Accounting\InvoiceService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoices)
    {
    }

    public function index(Request $request)
    {
        return Inertia::render('Erp/Invoices/Index', [
            'invoices' => $this->invoices->listInvoices($request->get('status')),
            'subscriptions' => Subscription::orderByDesc('id')->limit(50)->get(['id', 'subscription_number', 'status']),
            'providerDues' => ProviderDue::orderByDesc('id')->limit(50)->get(),
            'filters' => [
                'status' => $request->get('status'),
            ],
        ]);
    }

    public function store(StoreInvoiceRequest $request)
    {
        $subscription = Subscription::findOrFail($request->subscription_id);
        $this->invoices->createForSubscription(
            $subscription,
            (float) $request->amount,
            $request->notes
        );

        return redirect()
            ->route('erp.invoices.index')
            ->with('success', 'تم إنشاء الفاتورة');
    }

    public function pay(Request $request, int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $this->invoices->recordPayment($invoice, $data, $request->user()->id);

        return back()->with('success', 'تم تسجيل الدفع');
    }

    public function createProviderDue(Request $request)
    {
        $data = $request->validate([
            'visit_id' => 'required|integer|exists:visits,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $visit = Visit::findOrFail($data['visit_id']);
        abort_unless($visit->provider_id, 422, 'Visit has no provider');

        $this->invoices->createProviderDueFromVisit($visit, (float) $data['amount']);

        return back()->with('success', 'تم إنشاء مستحق المزود');
    }

    public function payProviderDue(int $id)
    {
        $due = ProviderDue::findOrFail($id);
        $this->invoices->markProviderDuePaid($due);

        return back()->with('success', 'تم تعليم المستحق كمدفوع');
    }

    public function sync()
    {
        $count = $this->invoices->syncOrdersToInvoices();

        return back()->with('success', "تمت مزامنة {$count} فاتورة من الطلبات");
    }
}
