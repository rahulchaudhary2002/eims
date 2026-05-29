<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentAcademicRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'                  => 'required|exists:students,id',
            'level'                       => 'required|string|max:50',
            'institution_name'            => 'nullable|string|max:255',
            'board'                       => 'nullable|string|max:100',
            'faculty'                     => 'nullable|string|max:150',
            'passed_year'                 => 'nullable|integer|min:1950|max:' . (date('Y') + 1),
            'gpa'                         => 'nullable|numeric|min:0|max:4',
            'percentage'                  => 'nullable|numeric|min:0|max:100',
            'symbol_number'               => 'nullable|string|max:100',
            'transcript_file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'character_certificate_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'is_verified'                 => 'nullable|boolean',
        ];
    }
}
