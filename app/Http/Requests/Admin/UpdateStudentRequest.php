<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id;

        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255', "unique:students,email,{$studentId}"],
            'phone'             => ['nullable', 'string', 'max:30', "unique:students,phone,{$studentId}"],
            'password'          => ['nullable', 'confirmed', Password::min(8)],
            'date_of_birth'     => ['nullable', 'date', 'before:today'],
            'gender'            => ['nullable', 'string', 'in:male,female,other'],
            'avatar'            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'         => ['nullable', 'boolean'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }
}
