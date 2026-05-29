<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_approved' => $this->boolean('is_approved'),
        ]);
    }

    public function rules(): array
    {
        return [
            'student_id'     => ['nullable', 'integer', 'exists:students,id'],
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'review'         => ['nullable', 'string', 'max:5000'],
            'is_approved'    => ['boolean'],
        ];
    }
}
