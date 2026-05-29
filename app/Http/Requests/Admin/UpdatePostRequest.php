<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'is_featured'  => $this->boolean('is_featured'),
        ]);
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
        }

        return [
            'institution_id' => ['nullable', $institutionRule],
            'created_by'     => ['nullable', Rule::exists('users', 'id')],
            'type'           => ['required', Rule::in(array_keys(Post::TYPES))],
            'title'          => ['required', 'string', 'max:255'],
            'content'        => ['nullable', 'string'],
            'thumbnail'      => ['nullable', 'image', 'max:5120'],
            'is_published'   => ['boolean'],
            'is_featured'    => ['boolean'],
            'published_at'   => ['nullable', 'date'],
        ];
    }
}
