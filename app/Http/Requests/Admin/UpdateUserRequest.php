<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'name'                              => ['required', 'string', 'max:255'],
            'email'                             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'                             => ['nullable', 'string', 'max:30', Rule::unique('users', 'phone')->ignore($user->id)],
            'password'                          => ['nullable', 'confirmed', Password::min(8)],
            'avatar'                            => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_super_admin'                    => ['nullable', 'boolean'],
            'is_active'                         => ['nullable', 'boolean'],
            'email_verified_at'                 => ['nullable', 'date'],
            'institutions'                      => ['nullable', 'array'],
            'institutions.*.institution_id'     => ['required', 'integer', 'exists:institutions,id'],
            'institutions.*.role_name'          => ['nullable', 'string', 'max:100'],
            'institutions.*.position'           => ['nullable', 'string', 'max:100'],
            'institutions.*.is_active'          => ['nullable'],
            'institutions.*.joined_at'          => ['nullable', 'date'],
            'primary_institution_id'            => ['nullable', 'integer', 'exists:institutions,id'],
        ];
    }
}
