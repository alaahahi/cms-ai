<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\CommissionResource;
use App\Http\Resources\V1\SaleResource;
use App\Http\Resources\V1\SalesRepResource;
use App\Http\Resources\V1\TargetResource;
use App\Models\Commission;
use App\Models\Target;
use App\Services\Sales\CommissionCalculationService;
use App\Services\Sales\SalesService;
use Illuminate\Http\Request;

class SalesController extends BaseApiController
{
    public function __construct(
        private SalesService $sales,
        private CommissionCalculationService $commissions
    ) {
    }

    public function reps()
    {
        return $this->success(SalesRepResource::collection($this->sales->listReps()));
    }

    public function showRep(int $id)
    {
        $rep = $this->sales->findRep($id);
        if (! $rep) {
            return $this->error('Sales rep not found', [], 404);
        }

        return $this->success(new SalesRepResource($rep));
    }

    public function index(Request $request)
    {
        $items = $this->sales->listSales(
            $request->filled('sales_rep_id') ? (int) $request->sales_rep_id : null
        );

        return $this->success(SaleResource::collection($items));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sales_rep_id' => 'required|integer|exists:sales_reps,id',
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'subscription_type_id' => 'nullable|integer|exists:subscription_types,id',
            'amount' => 'required|numeric|min:0',
            'cards_count' => 'nullable|integer|min:1',
            'sale_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $sale = $this->sales->recordSale($data);

        return $this->success(new SaleResource($sale), 'Sale recorded', 201);
    }

    public function commissions(Request $request)
    {
        $query = Commission::with('sale')->orderByDesc('id');

        if ($request->filled('sales_rep_id')) {
            $query->where('sales_rep_id', $request->sales_rep_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $this->success(CommissionResource::collection($query->limit(100)->get()));
    }

    public function payCommission(int $id)
    {
        $commission = Commission::find($id);
        if (! $commission) {
            return $this->error('Commission not found', [], 404);
        }

        return $this->success(
            new CommissionResource($this->commissions->markPaid($commission)),
            'Commission marked as paid'
        );
    }

    public function targets(Request $request)
    {
        $query = Target::query()->orderByDesc('year')->orderByDesc('month');

        if ($request->filled('sales_rep_id')) {
            $query->where('sales_rep_id', $request->sales_rep_id);
        }

        return $this->success(TargetResource::collection($query->limit(100)->get()));
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

        $target = $this->sales->setTarget(
            $data['sales_rep_id'],
            $data['year'],
            $data['month'],
            (float) $data['target_amount'],
            (int) ($data['target_cards'] ?? 0)
        );

        return $this->success(new TargetResource($target), 'Target saved', 201);
    }
}
