<?php

namespace App\Http\Requests\Admin;

use App\Models\Institution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institution = $this->route('institution');

        return [
            'parent_id'         => ['nullable', 'exists:institutions,id', Rule::notIn([$institution->id])],
            'type'              => ['required', Rule::in(array_keys(Institution::TYPES))],
            'name'              => ['required', 'string', 'max:255'],
            'slug'              => ['nullable', 'string', 'max:255', Rule::unique('institutions', 'slug')->ignore($institution->id)],
            'code'              => ['nullable', 'string', 'max:50', Rule::unique('institutions', 'code')->ignore($institution->id)],
            'email'             => ['nullable', 'email', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'website'           => ['nullable', 'url', 'max:255'],
            'logo'              => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cover_image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'established_year'  => ['nullable', 'integer', 'min:1800', 'max:' . (date('Y') + 1)],
            'country'           => ['nullable', 'string', 'max:100'],
            'province'          => ['nullable', 'string', 'max:100'],
            'district'          => ['nullable', 'string', 'max:100'],
            'city'              => ['nullable', 'string', 'max:100'],
            'address'           => ['nullable', 'string'],
            'latitude'          => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'         => ['nullable', 'numeric', 'between:-180,180'],
            'is_verified'       => ['boolean'],
            'status'            => ['required', Rule::in(array_keys(Institution::STATUSES))],
            'is_featured'       => ['boolean'],
            'sort_order'        => ['nullable', 'integer', 'min:0'],
        ];
    }
}
