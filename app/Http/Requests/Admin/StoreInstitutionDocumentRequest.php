<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id' => 'required|exists:institutions,id',
            'document_type'  => 'required|string|max:50',
            'title'          => 'required|string|max:255',
            'file_path'      => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,webp',
            'status'         => 'required|in:active,inactive,expired',
            'remarks'        => 'nullable|string|max:1000',
        ];
    }
}
