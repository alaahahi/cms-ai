<?php

namespace App\Services\Sales;

use App\Models\Commission;
use App\Models\Sale;
use App\Models\SalesRep;
use App\Services\BaseService;

class CommissionCalculationService extends BaseService
{
    public function calculateForSale(Sale $sale, ?SalesRep $rep = null): Commission
    {
        $rep = $rep ?: $sale->salesRep;
        $rate = (float) ($rep?->commission_rate ?? 0);
        $amount = round(((float) $sale->amount) * ($rate / 100), 2);

        return Commission::updateOrCreate(
            ['sale_id' => $sale->id],
            [
                'sales_rep_id' => $sale->sales_rep_id,
                'rate' => $rate,
                'amount' => $amount,
                'status' => Commission::STATUS_PENDING,
            ]
        );
    }

    public function markPaid(Commission $commission): Commission
    {
        $commission->update([
            'status' => Commission::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return $commission->fresh();
    }

    public function pendingTotalForRep(int $salesRepId): float
    {
        return (float) Commission::pending()->where('sales_rep_id', $salesRepId)->sum('amount');
    }
}
