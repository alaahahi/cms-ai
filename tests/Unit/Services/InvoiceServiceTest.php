<?php

namespace Tests\Unit\Services;

use App\Models\Invoice;
use App\Models\Subscription;
use App\Services\Accounting\InvoiceService;
use Tests\BaselineTestCase;

class InvoiceServiceTest extends BaselineTestCase
{
    public function test_create_invoice_and_mark_paid_via_payment()
    {
        $subscription = Subscription::factory()->create();
        $service = app(InvoiceService::class);

        $invoice = $service->createForSubscription($subscription, 200000, 'test');
        $this->assertEquals(Invoice::STATUS_SENT, $invoice->status);

        $payment = $service->recordPayment($invoice, [
            'amount' => 200000,
            'payment_method' => 'bank',
        ]);

        $this->assertEquals(200000.0, (float) $payment->amount);
        $this->assertEquals(Invoice::STATUS_PAID, $invoice->fresh()->status);
    }
}
