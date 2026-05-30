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
            'institution_id'         => ['required', 'integer', 'exists:institutions,id'],
            'institution_program_id' => ['required', 'integer', 'exists:institution_programs,id'],
            'scholarship_id'         => ['nullable', 'integer', 'exists:scholarships,id'],
            'source'                 => ['nullable', 'string', 'in:' . implode(',', array_keys(Application::SOURCES))],
            'student_message'        => ['nullable', 'string', 'max:2000'],
        ];
    }
}
