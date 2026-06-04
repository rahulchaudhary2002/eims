<?php

namespace App\Http\Requests\Admin;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');
        $institutionProgramRule = Rule::exists('institution_programs', 'id');
        $scholarshipRule = Rule::exists('scholarships', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $institutionProgramRule->where('institution_id', $scope);
            $scholarshipRule->where('institution_id', $scope);
        }

        return [
            'application_number'     => ['nullable', 'string', 'max:50', 'unique:applications,application_number'],
            'student_id'             => ['required', 'exists:students,id'],
            'institution_id'         => ['required', $institutionRule],
            'institution_program_id' => ['required', $institutionProgramRule],
            'scholarship_id'         => ['nullable', $scholarshipRule],
            'source'                 => ['required', Rule::in(array_keys(Application::SOURCES))],
            'status'                 => ['required', Rule::in(array_keys(Application::STATUSES))],
            'student_message'        => ['nullable', 'string'],
            'institution_remarks'    => ['nullable', 'string'],
            'admin_remarks'          => ['nullable', 'string'],
            'submitted_at'           => ['nullable', 'date'],
            'reviewed_at'            => ['nullable', 'date'],
            'referred_at'            => ['nullable', 'date'],
            'admitted_at'            => ['nullable', 'date'],
        ];
    }
}
