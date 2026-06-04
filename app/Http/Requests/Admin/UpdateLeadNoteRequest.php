<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadNoteRequest extends FormRequest
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
            'inquiry_id' => ['required', $inquiryRule],
            'user_id'    => ['required', Rule::exists('users', 'id')],
            'note'       => ['required', 'string'],
        ];
    }
}
