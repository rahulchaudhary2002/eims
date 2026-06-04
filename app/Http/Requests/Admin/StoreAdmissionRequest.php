<?php

namespace App\Http\Requests\Admin;

use App\Models\Admission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $applicationRule = Rule::exists('applications', 'id');
        $institutionRule = Rule::exists('institutions', 'id');
        $institutionId = (int) $this->input('institution_id');

        if ($institutionId > 0) {
            $applicationRule->where('institution_id', $institutionId);
        }

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $applicationRule->where('institution_id', $scope);
            $institutionRule->where('id', $scope);
        }

        return [
            'application_id'         => ['required', $applicationRule, 'unique:admissions,application_id'],
            'institution_id'         => ['required', $institutionRule],
            'admission_number'       => ['nullable', 'string', 'max:50', 'unique:admissions,admission_number'],
            'admission_date'         => ['required', 'date'],
            'paid_amount'            => ['nullable', 'numeric', 'min:0'],
            'payment_proof'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
            'verification_status'    => ['required', Rule::in(array_keys(Admission::VERIFICATION_STATUSES))],
            'verified_at'            => ['nullable', 'date'],
            'remarks'                => ['nullable', 'string'],
        ];
    }
}
