<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255', 'unique:subscription_plans,slug'],
            'price_monthly' => ['required', 'numeric', 'min:0'],
            'price_yearly'  => ['required', 'numeric', 'min:0'],
            'features_text' => ['nullable', 'string'],
            'is_active'     => ['boolean'],
        ];
    }
}
