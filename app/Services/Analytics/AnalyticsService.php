<?php

namespace App\Services\Analytics;

use App\Models\AnalyticsSnapshot;
use App\Models\Commission;
use App\Models\Invoice;
use App\Models\ProviderDue;
use App\Models\Sale;
use App\Models\Subscription;
use App\Models\Ticket;
use App\Models\Visit;
use App\Services\BaseService;

class AnalyticsService extends BaseService
{
    public function dashboardKpis(): array
    {
        return [
            'active_subscriptions' => Subscription::where('status', Subscription::STATUS_ACTIVE)->count(),
            'subscriptions_total' => Subscription::count(),
            'visits_total' => Visit::count(),
            'open_tickets' => Ticket::whereIn('status', [Ticket::STATUS_NEW, Ticket::STATUS_IN_PROGRESS])->count(),
            'sales_amount' => (float) Sale::sum('amount'),
            'pending_commissions' => (float) Commission::pending()->sum('amount'),
            'unpaid_invoices' => (float) Invoice::unpaid()->sum('amount'),
            'pending_provider_dues' => (float) ProviderDue::where('status', ProviderDue::STATUS_PENDING)->sum('amount'),
        ];
    }

    public function captureSnapshot(): array
    {
        $kpis = $this->dashboardKpis();
        $date = now()->toDateString();

        foreach ($kpis as $key => $value) {
            AnalyticsSnapshot::updateOrCreate(
                ['metric_key' => $key, 'snapshot_date' => $date],
                ['metric_value' => $value, 'meta' => null]
            );
        }

        return $kpis;
    }

    public function costAnalysis(): array
    {
        $medical = (float) ProviderDue::sum('amount');
        $commissions = (float) Commission::sum('amount');
        $revenue = (float) Invoice::where('status', Invoice::STATUS_PAID)->sum('amount');

        return [
            'revenue' => $revenue,
            'medical_costs' => $medical,
            'commission_costs' => $commissions,
            'gross_margin' => $revenue - $medical - $commissions,
        ];
    }

    public function alerts(): array
    {
        $alerts = [];

        $openTickets = Ticket::where('status', Ticket::STATUS_NEW)->count();
        if ($openTickets > 0) {
            $alerts[] = [
                'type' => 'tickets',
                'level' => 'warning',
                'message' => "There are {$openTickets} new tickets awaiting assignment",
            ];
        }

        $overdue = Invoice::where('status', Invoice::STATUS_SENT)
            ->whereNotNull('due_date')
            ->where('due_date', '<', now()->toDateString())
            ->count();
        if ($overdue > 0) {
            $alerts[] = [
                'type' => 'invoices',
                'level' => 'danger',
                'message' => "{$overdue} invoices are overdue",
            ];
        }

        $dues = ProviderDue::where('status', ProviderDue::STATUS_PENDING)->count();
        if ($dues > 0) {
            $alerts[] = [
                'type' => 'provider_dues',
                'level' => 'info',
                'message' => "{$dues} provider dues are pending payment",
            ];
        }

        return $alerts;
    }
}
