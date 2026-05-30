<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateStudentPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password:student'],
            'password'         => ['required', Password::defaults(), 'confirmed'],
        ];
    }
}
