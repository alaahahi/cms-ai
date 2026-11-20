<?php

namespace App\Services\Sales;

use App\Models\Sale;
use App\Models\SalesRep;
use App\Models\Subscription;
use App\Models\Target;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Collection;

class SalesService extends BaseService
{
    public function __construct(
        private LegacySalesSyncService $sync,
        private CommissionCalculationService $commissions
    ) {
    }

    public function listReps(): Collection
    {
        $reps = SalesRep::with('user')->active()->orderBy('id')->get();
        if ($reps->isNotEmpty()) {
            return $reps;
        }

        $this->sync->syncAll();

        return SalesRep::with('user')->active()->orderBy('id')->get();
    }

    public function findRep(int $id): ?SalesRep
    {
        return SalesRep::with(['user', 'targets', 'commissions'])->find($id);
    }

    public function recordSale(array $data): Sale
    {
        return $this->transaction(function () use ($data) {
            $rep = SalesRep::findOrFail($data['sales_rep_id']);

            $sale = Sale::create([
                'sales_rep_id' => $rep->id,
                'subscription_id' => $data['subscription_id'] ?? null,
                'legacy_profile_id' => $data['legacy_profile_id'] ?? null,
                'subscription_type_id' => $data['subscription_type_id'] ?? null,
                'amount' => $data['amount'] ?? 0,
                'cards_count' => $data['cards_count'] ?? 1,
                'sale_date' => $data['sale_date'] ?? now()->toDateString(),
                'status' => Sale::STATUS_COMPLETED,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->commissions->calculateForSale($sale->fresh('salesRep'), $rep);
            $this->sync->bumpTarget($rep, $sale);

            return $sale->fresh(['salesRep.user', 'commission']);
        });
    }

    public function ensureRepForUser(User $user): SalesRep
    {
        return $this->sync->syncSalesRep($user);
    }

    public function listSales(?int $salesRepId = null): Collection
    {
        $query = Sale::with(['salesRep.user', 'commission'])->orderByDesc('id');

        if ($salesRepId) {
            $query->where('sales_rep_id', $salesRepId);
        }

        return $query->limit(100)->get();
    }

    public function setTarget(int $salesRepId, int $year, int $month, float $targetAmount, int $targetCards = 0): Target
    {
        return Target::updateOrCreate(
            [
                'sales_rep_id' => $salesRepId,
                'year' => $year,
                'month' => $month,
            ],
            [
                'target_amount' => $targetAmount,
                'target_cards' => $targetCards,
            ]
        );
    }

    public function linkSubscriptionSale(Subscription $subscription, int $salesRepUserId, float $amount = 0): Sale
    {
        $rep = $this->ensureRepForUser(User::findOrFail($salesRepUserId));

        return $this->recordSale([
            'sales_rep_id' => $rep->id,
            'subscription_id' => $subscription->id,
            'legacy_profile_id' => $subscription->legacy_profile_id,
            'subscription_type_id' => $subscription->subscription_type_id,
            'amount' => $amount,
            'cards_count' => 1,
            'sale_date' => optional($subscription->start_date)->toDateString() ?? now()->toDateString(),
            'notes' => 'Linked to subscription #'.$subscription->id,
        ]);
    }
}
