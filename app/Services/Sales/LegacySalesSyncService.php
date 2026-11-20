<?php

namespace App\Services\Sales;

use App\Models\Profile;
use App\Models\Sale;
use App\Models\SalesRep;
use App\Models\Subscription;
use App\Models\SubscriptionType;
use App\Models\Target;
use App\Models\User;
use App\Models\UserType;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

class LegacySalesSyncService extends BaseService
{
    public function __construct(private CommissionCalculationService $commissions)
    {
    }

    public function syncAll(): array
    {
        $reps = 0;
        $sales = 0;

        $selesTypeId = UserType::where('name', 'seles')->value('id');

        $query = User::query()->withTrashed();
        if ($selesTypeId) {
            $query->where('type_id', $selesTypeId);
        } else {
            $query->whereHas('roles', fn ($q) => $q->whereIn('name', ['seles', 'sales_rep']));
        }

        $query->orderBy('id')->chunkById(100, function ($users) use (&$reps) {
            foreach ($users as $user) {
                $this->syncSalesRep($user);
                $reps++;
            }
        });

        Profile::query()
            ->whereNotNull('user_add')
            ->orderBy('id')
            ->chunkById(100, function ($profiles) use (&$sales) {
                foreach ($profiles as $profile) {
                    if ($this->syncSaleFromProfile($profile)) {
                        $sales++;
                    }
                }
            });

        return compact('reps', 'sales');
    }

    public function syncSalesRep(User $user): SalesRep
    {
        return SalesRep::updateOrCreate(
            ['user_id' => $user->id],
            [
                'commission_rate' => $user->percentage ?? 0,
                'is_active' => $user->deleted_at === null,
                'code' => 'SR-'.$user->id,
            ]
        );
    }

    public function syncSaleFromProfile(Profile $profile): ?Sale
    {
        if (! $profile->user_add) {
            return null;
        }

        $repUser = User::withTrashed()->find($profile->user_add);
        if (! $repUser) {
            return null;
        }

        $rep = $this->syncSalesRep($repUser);

        $subscriptionId = Subscription::where('legacy_profile_id', $profile->id)->value('id');
        $typeId = null;
        if ($profile->card_id) {
            $typeId = SubscriptionType::where('legacy_card_id', $profile->card_id)->value('id');
        }

        $sale = Sale::updateOrCreate(
            ['legacy_profile_id' => $profile->id],
            [
                'sales_rep_id' => $rep->id,
                'subscription_id' => $subscriptionId,
                'subscription_type_id' => $typeId,
                'amount' => 0,
                'cards_count' => 1,
                'sale_date' => $this->parseDate($profile->created) ?? optional($profile->created_at)->toDateString(),
                'status' => Sale::STATUS_COMPLETED,
                'notes' => 'Synced from profile #'.$profile->id,
            ]
        );

        $this->commissions->calculateForSale($sale->fresh('salesRep'));
        $this->bumpTarget($rep, $sale);

        return $sale;
    }

    public function bumpTarget(SalesRep $rep, Sale $sale): void
    {
        $date = $sale->sale_date ? Carbon::parse($sale->sale_date) : now();

        $target = Target::firstOrCreate(
            [
                'sales_rep_id' => $rep->id,
                'year' => (int) $date->format('Y'),
                'month' => (int) $date->format('n'),
            ],
            [
                'target_amount' => 0,
                'target_cards' => 0,
                'achieved_amount' => 0,
                'achieved_cards' => 0,
            ]
        );

        $target->increment('achieved_cards', (int) $sale->cards_count);
        $target->increment('achieved_amount', (float) $sale->amount);
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
