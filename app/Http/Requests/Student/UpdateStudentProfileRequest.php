<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        $studentId = auth('student')->id();

        return [
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender'        => ['nullable', 'in:male,female,other'],
            'avatar'        => ['nullable', 'image', 'max:2048'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('students')->ignore($studentId)],
        ];
    }
}
