<?php

namespace App\Http\Requests\Admin;

use App\Models\Referral;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReferralRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');
        $applicationRule = Rule::exists('applications', 'id');
        $applicationUniqueRule = Rule::unique('referrals', 'application_id')->where(fn ($query) => $query->whereNull('deleted_at'));
        $institutionId = (int) $this->input('institution_id');

        if ($institutionId > 0) {
            $applicationRule->where('institution_id', $institutionId);
        }

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $applicationRule->where('institution_id', $scope);
        }

        return [
            'referral_number' => ['nullable', 'string', 'max:255', 'unique:referrals,referral_number'],
            'application_id'  => ['required', $applicationRule, $applicationUniqueRule],
            'institution_id'  => ['required', $institutionRule],
            'status'          => ['required', Rule::in(array_keys(Referral::STATUSES))],
            'referred_at'     => ['nullable', 'date'],
            'viewed_at'       => ['nullable', 'date'],
        ];
    }
}
