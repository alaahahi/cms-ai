<?php

namespace Tests\Unit\Services;

use App\Models\Commission;
use App\Models\Sale;
use App\Models\SalesRep;
use App\Services\Sales\CommissionCalculationService;
use App\Services\Sales\SalesService;
use Tests\BaselineTestCase;

class CommissionCalculationServiceTest extends BaselineTestCase
{
    public function test_calculates_commission_from_rate_and_amount()
    {
        $rep = SalesRep::factory()->create(['commission_rate' => 10]);
        $sale = Sale::factory()->create([
            'sales_rep_id' => $rep->id,
            'amount' => 1000000,
        ]);

        $commission = app(CommissionCalculationService::class)->calculateForSale($sale->fresh('salesRep'), $rep);

        $this->assertEquals(10, (float) $commission->rate);
        $this->assertEquals(100000, (float) $commission->amount);
        $this->assertEquals(Commission::STATUS_PENDING, $commission->status);
    }

    public function test_record_sale_creates_commission_and_updates_target()
    {
        $rep = SalesRep::factory()->create(['commission_rate' => 5]);

        $sale = app(SalesService::class)->recordSale([
            'sales_rep_id' => $rep->id,
            'amount' => 200000,
            'cards_count' => 2,
            'sale_date' => now()->toDateString(),
        ]);

        $this->assertNotNull($sale->commission);
        $this->assertEquals(10000, (float) $sale->commission->amount);

        $this->assertDatabaseHas('targets', [
            'sales_rep_id' => $rep->id,
            'year' => (int) now()->format('Y'),
            'month' => (int) now()->format('n'),
            'achieved_cards' => 2,
        ]);
    }
}
