<?php

namespace Tests\Feature\Baseline;

use Tests\BaselineTestCase;
use App\Models\Order;

/**
 * Baseline tests for Order and Payment functionality
 * Testing current payment flow (QiPay integration is mocked)
 */
class OrderPaymentTest extends BaselineTestCase
{
    public function test_order_can_be_created()
    {
        $order = Order::factory()->create([
            'amount' => 850000,
            'currency' => 'IQD',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('orders', [
            'amount' => 850000,
            'status' => 'pending',
        ]);
    }

    public function test_order_has_all_required_fields()
    {
        $order = Order::factory()->create([
            'name' => 'أحمد محمد',
            'phone' => '07901234567',
            'address' => 'بغداد',
            'amount' => 850000,
        ]);

        $this->assertEquals('أحمد محمد', $order->name);
        $this->assertEquals('07901234567', $order->phone);
        $this->assertEquals('بغداد', $order->address);
        $this->assertEquals(850000, $order->amount);
    }

    public function test_order_can_be_completed()
    {
        $order = Order::factory()->completed()->create();

        $this->assertEquals('completed', $order->status);
        $this->assertEquals('success', $order->state);
    }

    public function test_order_stores_transaction_ids()
    {
        $order = Order::factory()->create([
            'transactionId' => 'TXN-123456',
            '3DSecureId' => '3DS-123456',
        ]);

        $this->assertNotNull($order->transactionId);
        $this->assertNotNull($order->{'3DSecureId'});
    }
}
