<?php

namespace App\Services\Accounting;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProviderDue;
use App\Models\Subscription;
use App\Models\Visit;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InvoiceService extends BaseService
{
    public function syncOrdersToInvoices(): int
    {
        $count = 0;
        Order::query()->orderBy('id')->chunkById(100, function ($orders) use (&$count) {
            foreach ($orders as $order) {
                Invoice::updateOrCreate(
                    ['legacy_order_id' => $order->id],
                    [
                        'invoice_number' => 'INV-ORD-'.$order->id,
                        'amount' => $order->amount ?? 0,
                        'currency' => $order->currency ?? 'IQD',
                        'status' => $order->status === 'completed' ? Invoice::STATUS_PAID : Invoice::STATUS_SENT,
                        'paid_at' => $order->status === 'completed' ? $order->updated_at : null,
                        'notes' => 'Synced from order '.$order->order_id,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }

    public function createForSubscription(Subscription $subscription, float $amount, ?string $notes = null): Invoice
    {
        return Invoice::create([
            'invoice_number' => 'INV-'.Str::upper(Str::random(8)),
            'subscription_id' => $subscription->id,
            'amount' => $amount,
            'currency' => 'IQD',
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => Invoice::STATUS_SENT,
            'notes' => $notes,
        ]);
    }

    public function recordPayment(Invoice $invoice, array $data, ?int $receivedBy = null): Payment
    {
        return $this->transaction(function () use ($invoice, $data, $receivedBy) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'amount' => $data['amount'] ?? $invoice->amount,
                'transaction_id' => $data['transaction_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'received_by' => $receivedBy,
            ]);

            $paidTotal = (float) $invoice->payments()->sum('amount');
            if ($paidTotal >= (float) $invoice->amount) {
                $invoice->update([
                    'status' => Invoice::STATUS_PAID,
                    'paid_at' => now(),
                ]);
            }

            return $payment->fresh('invoice');
        });
    }

    public function createProviderDueFromVisit(Visit $visit, float $amount): ProviderDue
    {
        if (! $visit->provider_id) {
            throw new \InvalidArgumentException('Visit has no provider');
        }

        return ProviderDue::updateOrCreate(
            ['visit_id' => $visit->id],
            [
                'provider_id' => $visit->provider_id,
                'amount' => $amount,
                'period_start' => now()->startOfMonth()->toDateString(),
                'period_end' => now()->endOfMonth()->toDateString(),
                'status' => ProviderDue::STATUS_PENDING,
            ]
        );
    }

    public function markProviderDuePaid(ProviderDue $due): ProviderDue
    {
        $due->update([
            'status' => ProviderDue::STATUS_PAID,
            'paid_at' => now(),
        ]);

        return $due->fresh();
    }

    public function listInvoices(?string $status = null): Collection
    {
        $query = Invoice::with('payments')->orderByDesc('id');
        if ($status) {
            $query->where('status', $status);
        }

        return $query->limit(100)->get();
    }
}
