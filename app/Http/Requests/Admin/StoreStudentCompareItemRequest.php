<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentCompareItemRequest extends FormRequest
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
            'student_id'             => ['required', Rule::exists('students', 'id')],
            'institution_id'         => ['required', $institutionRule],
            'institution_program_id' => ['nullable', $institutionProgramRule],
        ];
    }
}
