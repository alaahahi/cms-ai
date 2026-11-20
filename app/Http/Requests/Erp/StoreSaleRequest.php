<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'sales_rep_id' => 'required|integer|exists:sales_reps,id',
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'subscription_type_id' => 'nullable|integer|exists:subscription_types,id',
            'amount' => 'required|numeric|min:0',
            'cards_count' => 'nullable|integer|min:1',
            'sale_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }
}
