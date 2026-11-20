<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:low,medium,high',
            'category_id' => 'nullable|integer|exists:ticket_categories,id',
            'subscription_id' => 'nullable|integer|exists:subscriptions,id',
            'message' => 'nullable|string',
        ];
    }
}
