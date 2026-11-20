<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'card_number' => 'required|string|max:100|unique:profile,card_number',
            'card_id' => 'nullable|integer|exists:card,id',
            'address' => 'nullable|string',
            'family_name' => 'nullable|string',
            'source' => 'nullable|string|max:50',
        ];
    }
}
