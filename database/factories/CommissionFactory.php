<?php

namespace Database\Factories;

use App\Models\Commission;
use App\Models\Sale;
use App\Models\SalesRep;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    public function definition()
    {
        return [
            'sales_rep_id' => SalesRep::factory(),
            'sale_id' => Sale::factory(),
            'rate' => 10,
            'amount' => 85000,
            'status' => Commission::STATUS_PENDING,
        ];
    }
}
