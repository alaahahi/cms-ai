<?php

namespace App\Http\Requests\Erp;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'visit_type' => 'required|in:lab,doctor',
            'profile_id' => 'required|integer|exists:profile,id',
            'husband_hb' => 'nullable|string|max:50',
            'wife_hb' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ];
    }
}
