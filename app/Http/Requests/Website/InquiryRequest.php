<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id'         => ['nullable', 'integer', 'exists:institutions,id'],
            'applicable_type'        => ['nullable', 'string'],
            'applicable_id'          => ['nullable', 'integer', 'min:1'],
            'name'                   => ['required', 'string', 'max:255'],
            'email'                  => ['required', 'email', 'max:255'],
            'phone'                  => ['required', 'string', 'max:20'],
            'message'                => ['required', 'string', 'max:2000'],
            'source'                 => ['nullable', 'string', 'in:website,institution_page,program_page,scholarship_page,contact_page'],
        ];
    }
}
