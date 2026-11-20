<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subscription_id' => 'required|integer|exists:subscriptions,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }
}
