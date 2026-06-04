<?php

namespace App\Http\Requests\Admin;

use App\Models\InstitutionSubscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstitutionSubscriptionRequest extends FormRequest
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
            'institution_id'       => ['required', $institutionRule],
            'subscription_plan_id' => ['required', Rule::exists('subscription_plans', 'id')],
            'starts_at'            => ['required', 'date'],
            'ends_at'              => ['nullable', 'date', 'after_or_equal:starts_at'],
            'billing_cycle'        => ['required', Rule::in(array_keys(InstitutionSubscription::BILLING_CYCLES))],
            'amount'               => ['required', 'numeric', 'min:0'],
            'status'               => ['required', Rule::in(array_keys(InstitutionSubscription::STATUSES))],
        ];
    }
}
