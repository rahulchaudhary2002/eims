<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'    => 'required|exists:students,id',
            'document_type' => 'required|string|max:50',
            'title'         => 'required|string|max:255',
            'file_path'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status'        => 'required|string|in:active,inactive,expired',
            'remarks'       => 'nullable|string|max:1000',
        ];
    }
}
