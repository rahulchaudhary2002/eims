<?php

namespace App\Http\Requests\Student;

use App\Models\StudentAcademicRecord;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentAcademicRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'level'                      => ['required', 'in:' . implode(',', array_keys(StudentAcademicRecord::LEVELS))],
            'institution_name'           => ['required', 'string', 'max:255'],
            'board'                      => ['nullable', 'string', 'max:100'],
            'faculty'                    => ['nullable', 'string', 'max:100'],
            'passed_year'                => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'grade_type'                 => ['required', 'in:gpa,percentage'],
            'gpa'                        => ['nullable', 'numeric', 'min:0', 'max:4', 'required_if:grade_type,gpa'],
            'percentage'                 => ['nullable', 'numeric', 'min:0', 'max:100', 'required_if:grade_type,percentage'],
            'symbol_number'              => ['nullable', 'string', 'max:50'],
            'transcript_file'            => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'character_certificate_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
