<?php

namespace App\Http\Requests\Admin;

use App\Models\Scholarship;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScholarshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');
        $institutionProgramRule = Rule::exists('institution_programs', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
            $institutionProgramRule->where('institution_id', $scope);
        }

        return [
            'institution_id'         => ['required', $institutionRule],
            'institution_program_id' => ['required', $institutionProgramRule],
            'type'                   => ['required', Rule::in(array_keys(Scholarship::TYPES))],
            'title'                  => ['required', 'string', 'max:255'],
            'slug'                   => ['nullable', 'string', 'max:255', 'unique:scholarships,slug'],
            'description'            => ['nullable', 'string'],
            'minimum_gpa'            => ['nullable', 'numeric', 'min:0', 'max:4'],
            'minimum_percentage'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'benefit_type'           => ['required', Rule::in(array_keys(Scholarship::BENEFIT_TYPES))],
            'benefit_value'          => ['nullable', 'numeric', 'min:0'],
            'total_slots'            => ['nullable', 'integer', 'min:0'],
            'used_slots'             => ['nullable', 'integer', 'min:0'],
            'start_date'             => ['nullable', 'date'],
            'end_date'               => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'                 => ['required', Rule::in(array_keys(Scholarship::STATUSES))],
        ];
    }
}
