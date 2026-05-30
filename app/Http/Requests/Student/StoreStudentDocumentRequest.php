<?php

namespace App\Http\Requests\Student;

use App\Models\StudentDocument;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', 'in:' . implode(',', array_keys(StudentDocument::DOCUMENT_TYPES))],
            'title'         => ['required', 'string', 'max:255'],
            'file_path'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'remarks'       => ['nullable', 'string', 'max:500'],
        ];
    }
}
