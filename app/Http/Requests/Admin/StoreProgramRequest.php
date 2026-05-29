<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_id'  => 'required|exists:faculties,id',
            'level'       => 'nullable|string|max:100',
            'name'        => 'required|string|max:255|unique:programs,name',
            'slug'        => 'nullable|string|max:255|unique:programs,slug|regex:/^[a-z0-9\-]+$/',
            'description' => 'nullable|string|max:5000',
            'is_active'   => 'boolean',
        ];
    }
}
