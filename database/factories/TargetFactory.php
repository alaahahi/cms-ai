<?php

namespace Database\Factories;

use App\Models\SalesRep;
use App\Models\Target;
use Illuminate\Database\Eloquent\Factories\Factory;

class TargetFactory extends Factory
{
    protected $model = Target::class;

    public function definition()
    {
        return [
            'sales_rep_id' => SalesRep::factory(),
            'year' => (int) now()->format('Y'),
            'month' => (int) now()->format('n'),
            'target_amount' => 5000000,
            'target_cards' => 10,
            'achieved_amount' => 0,
            'achieved_cards' => 0,
        ];
    }
}
