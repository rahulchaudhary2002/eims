<?php

namespace App\Http\Requests\Admin;

use App\Models\LeadFollowUp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadFollowUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $inquiryRule = Rule::exists('inquiries', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $inquiryRule->where('institution_id', $scope);
        }

        return [
            'inquiry_id'   => ['required', $inquiryRule],
            'assigned_to'  => ['nullable', Rule::exists('users', 'id')],
            'follow_up_at' => ['required', 'date'],
            'status'       => ['required', Rule::in(array_keys(LeadFollowUp::STATUSES))],
            'remarks'      => ['nullable', 'string'],
        ];
    }
}
