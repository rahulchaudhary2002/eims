<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentScholarshipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'scholarship_id' => ['required', 'integer', 'exists:scholarships,id'],
            'application_id' => ['nullable', 'integer', 'exists:applications,id'],
            'remarks'        => ['nullable', 'string', 'max:2000'],
        ];
    }
}
