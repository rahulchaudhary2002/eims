<?php

namespace App\Http\Requests\Admin;

use App\Models\PostMedia;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postRule = Rule::exists('posts', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $postRule->where('institution_id', $scope);
        }

        return [
            'post_id'   => ['required', $postRule],
            'type'      => ['required', Rule::in(array_keys(PostMedia::TYPES))],
            'file_path' => ['required', 'file', 'max:51200'],
            'caption'   => ['nullable', 'string', 'max:500'],
        ];
    }
}
