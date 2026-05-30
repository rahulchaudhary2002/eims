<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'review'         => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }
}
