<?php

namespace App\Http\Requests\Student;

use App\Models\Inquiry;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'institution_id'         => ['nullable', 'integer', 'exists:institutions,id'],
            'institution_program_id' => ['nullable', 'integer', 'exists:institution_programs,id'],
            'name'                   => ['nullable', 'string', 'max:255'],
            'email'                  => ['nullable', 'email', 'max:255'],
            'phone'                  => ['nullable', 'string', 'max:30'],
            'message'                => ['required', 'string', 'max:2000'],
            'source'                 => ['nullable', 'in:' . implode(',', array_keys(Inquiry::SOURCES))],
        ];
    }
}
