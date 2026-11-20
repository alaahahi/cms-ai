<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'invoice_number' => 'INV-'.Str::upper(Str::random(8)),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'currency' => 'IQD',
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => Invoice::STATUS_SENT,
        ];
    }
}
