<?php

namespace App\Http\Requests\Admin;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule        = Rule::exists('institutions', 'id');
        $institutionProgramRule = Rule::exists('institution_programs', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $institutionProgramRule->where('institution_id', $scope);
        }

        return [
            'student_id'              => ['nullable', Rule::exists('students', 'id')],
            'institution_id'          => ['nullable', $institutionRule],
            'institution_program_id'  => ['nullable', $institutionProgramRule],
            'name'                    => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255'],
            'phone'                   => ['nullable', 'string', 'max:50'],
            'message'                 => ['nullable', 'string'],
            'source'                  => ['nullable', Rule::in(array_keys(Inquiry::SOURCES))],
            'status'                  => ['required', Rule::in(array_keys(Inquiry::STATUSES))],
            'assigned_to'             => ['nullable', Rule::exists('users', 'id')],
            'last_contacted_at'       => ['nullable', 'date'],
        ];
    }
}
