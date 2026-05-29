<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $faculty = $this->route('faculty');

        return [
            'name'      => 'required|string|max:255|unique:faculties,name,' . $faculty->id,
            'slug'      => 'nullable|string|max:255|unique:faculties,slug,' . $faculty->id . '|regex:/^[a-z0-9\-]+$/',
            'is_active' => 'boolean',
        ];
    }
}
