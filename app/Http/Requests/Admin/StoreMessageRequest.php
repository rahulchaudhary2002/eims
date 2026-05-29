<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message'    => ['nullable', 'string', 'max:10000'],
            'attachment' => ['nullable', 'file', 'max:10240'],
            'sender_type' => ['required', 'string', 'in:' . implode(',', array_keys(\App\Models\Message::SENDER_TYPES))],
            'sender_id'   => ['required', 'integer'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if (empty($this->input('message')) && ! $this->hasFile('attachment')) {
                $v->errors()->add('message', 'A message or attachment is required.');
            }
        });
    }
}
