<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255|unique:faculties,name',
            'slug'      => 'nullable|string|max:255|unique:faculties,slug|regex:/^[a-z0-9\-]+$/',
            'is_active' => 'boolean',
        ];
    }
}
