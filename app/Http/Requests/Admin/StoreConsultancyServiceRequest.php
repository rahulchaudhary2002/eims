<?php

namespace App\Http\Requests\Admin;

use App\Models\ConsultancyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsultancyServiceRequest extends FormRequest
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
            'service_type'   => ['required', Rule::in(array_keys(ConsultancyService::SERVICE_TYPES))],
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'service_fee'    => ['nullable', 'numeric', 'min:0'],
            'is_active'      => ['boolean'],
        ];
    }
}
