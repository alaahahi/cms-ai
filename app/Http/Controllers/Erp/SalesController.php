<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use App\Http\Requests\Erp\StoreSaleRequest;
use App\Models\Commission;
use App\Models\Target;
use App\Services\Sales\CommissionCalculationService;
use App\Services\Sales\SalesService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function __construct(
        private SalesService $sales,
        private CommissionCalculationService $commissions
    ) {
    }

    public function index(Request $request)
    {
        return Inertia::render('Erp/Sales/Index', [
            'reps' => $this->sales->listReps(),
            'sales' => $this->sales->listSales(
                $request->filled('sales_rep_id') ? (int) $request->sales_rep_id : null
            ),
            'commissions' => Commission::with('sale')->orderByDesc('id')->limit(50)->get(),
            'targets' => Target::query()->orderByDesc('year')->orderByDesc('month')->limit(50)->get(),
            'filters' => [
                'sales_rep_id' => $request->get('sales_rep_id'),
            ],
        ]);
    }

    public function store(StoreSaleRequest $request)
    {
        $this->sales->recordSale($request->validated());

        return redirect()
            ->route('erp.sales.index')
            ->with('success', 'تم تسجيل عملية البيع');
    }

    public function payCommission(int $id)
    {
        $commission = Commission::findOrFail($id);
        $this->commissions->markPaid($commission);

        return back()->with('success', 'تم تعليم العمولة كمدفوعة');
    }

    public function storeTarget(Request $request)
    {
        $data = $request->validate([
            'sales_rep_id' => 'required|integer|exists:sales_reps,id',
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'target_amount' => 'required|numeric|min:0',
            'target_cards' => 'nullable|integer|min:0',
        ]);

        $this->sales->setTarget(
            $data['sales_rep_id'],
            $data['year'],
            $data['month'],
            (float) $data['target_amount'],
            (int) ($data['target_cards'] ?? 0)
        );

        return back()->with('success', 'تم حفظ الهدف');
    }
}
