<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Conversation;

class UpdateConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'     => ['nullable', 'integer', 'exists:students,id'],
            'institution_id' => ['nullable', 'integer', 'exists:institutions,id'],
            'type'           => ['required', 'string', 'in:' . implode(',', array_keys(Conversation::TYPES))],
        ];
    }
}
