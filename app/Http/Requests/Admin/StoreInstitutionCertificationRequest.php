<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstitutionCertificationRequest extends FormRequest
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
        $institutionRule = Rule::exists('institutions', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
        }

        return [
            'institution_id' => ['required', $institutionRule],
            'title'          => ['required', 'string', 'max:255'],
            'fee'            => ['nullable', 'numeric', 'min:0'],
            'duration_hours' => ['nullable', 'integer', 'min:1'],
            'description'    => ['nullable', 'string'],
            'is_active'      => ['boolean'],
        ];
    }
}
