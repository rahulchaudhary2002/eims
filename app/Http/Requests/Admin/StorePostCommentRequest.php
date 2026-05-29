<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_hidden' => $this->boolean('is_hidden')]);
    }

    public function rules(): array
    {
        $postRule = Rule::exists('posts', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $postRule->where('institution_id', $scope);
        }

        return [
            'post_id'          => ['required', $postRule],
            'parent_id'        => ['nullable', Rule::exists('post_comments', 'id')],
            'commentable_type' => ['required', 'string'],
            'commentable_id'   => ['required', 'integer'],
            'comment'          => ['required', 'string'],
            'is_hidden'        => ['boolean'],
        ];
    }
}
