<?php

namespace Database\Factories;

use App\Models\Sale;
use App\Models\SalesRep;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition()
    {
        return [
            'sales_rep_id' => SalesRep::factory(),
            'amount' => 850000,
            'cards_count' => 1,
            'sale_date' => now()->toDateString(),
            'status' => Sale::STATUS_COMPLETED,
            'notes' => 'factory sale',
        ];
    }
}
