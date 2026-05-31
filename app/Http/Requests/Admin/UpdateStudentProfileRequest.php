<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'          => [
                'required',
                'exists:students,id',
                Rule::unique('student_profiles', 'student_id')->ignore($this->route('student_profile')),
            ],
            'guardian_name'       => 'nullable|string|max:255',
            'guardian_phone'      => 'nullable|string|max:30',
            'province'            => 'nullable|string|max:100',
            'district'            => 'nullable|string|max:100',
            'city'                => 'nullable|string|max:100',
            'address'             => 'nullable|string|max:1000',
            'budget_min'          => 'nullable|integer|min:0',
            'budget_max'          => 'nullable|integer|min:0|gte:budget_min',
            'preferred_location'  => 'nullable|string|max:255',
            'career_interests'    => 'nullable|string',
            'preferred_faculties' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'budget_max.gte' => 'Maximum budget must be greater than or equal to the minimum.',
        ];
    }
}
