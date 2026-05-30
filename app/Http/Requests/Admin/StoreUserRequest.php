<?php

namespace App\Http\Requests\Admin;

use App\Models\UserInstitution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                              => ['required', 'string', 'max:255'],
            'email'                             => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'                             => ['nullable', 'string', 'max:30', 'unique:users,phone'],
            'password'                          => ['required', 'confirmed', Password::min(8)],
            'avatar'                            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active'                         => ['nullable', 'boolean'],
            'email_verified_at'                 => ['nullable', 'date'],
            'institutions'                      => ['nullable', 'array'],
            'institutions.*.institution_id'     => ['required', 'integer', 'exists:institutions,id'],
            'institutions.*.role_name'          => ['nullable', Rule::in(array_keys(UserInstitution::ROLES))],
            'institutions.*.position'           => ['nullable', 'string', 'max:100'],
            'institutions.*.is_active'          => ['nullable'],
            'institutions.*.joined_at'          => ['nullable', 'date'],
            'primary_institution_id'            => ['nullable', 'integer', 'exists:institutions,id'],
        ];
    }
}
