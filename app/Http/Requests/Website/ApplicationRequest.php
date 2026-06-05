<?php

namespace App\Http\Requests\Website;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('student')->check();
    }

    public function rules(): array
    {
        return [
            'institution_id'  => ['required', 'integer', 'exists:institutions,id'],
            'applicable_type' => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Application::APPLICABLE_TYPES))],
            'applicable_id'   => ['required', 'integer', 'min:1'],
            'scholarship_id'         => ['nullable', 'integer', 'exists:scholarships,id'],
            'source'                 => ['nullable', 'string', 'in:' . implode(',', array_keys(Application::SOURCES))],
            'student_message'        => ['nullable', 'string', 'max:2000'],
        ];
    }
}
