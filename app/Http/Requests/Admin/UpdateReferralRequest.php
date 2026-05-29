<?php

namespace App\Http\Requests\Admin;

use App\Models\Referral;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReferralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');
        $applicationRule = Rule::exists('applications', 'id');
        $referral = $this->route('referral');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $applicationRule->where('institution_id', $scope);
        }

        return [
            'referral_number' => ['nullable', 'string', 'max:255', Rule::unique('referrals', 'referral_number')->ignore($referral?->id)],
            'application_id'  => ['required', $applicationRule],
            'student_id'      => ['required', Rule::exists('students', 'id')],
            'institution_id'  => ['required', $institutionRule],
            'referred_by'     => ['required', Rule::exists('users', 'id')],
            'status'          => ['required', Rule::in(array_keys(Referral::STATUSES))],
            'referred_at'     => ['nullable', 'date'],
            'viewed_at'       => ['nullable', 'date'],
        ];
    }
}
