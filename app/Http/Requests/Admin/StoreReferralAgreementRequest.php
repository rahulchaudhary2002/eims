<?php

namespace App\Http\Requests\Admin;

use App\Models\ReferralAgreement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReferralAgreementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
        }

        return [
            'institution_id'              => ['required', $institutionRule],
            'commission_type'             => ['required', Rule::in(array_keys(ReferralAgreement::COMMISSION_TYPES))],
            'commission_value'            => ['required', 'numeric', 'min:0'],
            'student_cashback_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'platform_revenue_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'start_date'                  => ['nullable', 'date'],
            'end_date'                    => ['nullable', 'date', 'after_or_equal:start_date'],
            'agreement_file'              => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'status'                      => ['required', Rule::in(array_keys(ReferralAgreement::STATUSES))],
        ];
    }
}
