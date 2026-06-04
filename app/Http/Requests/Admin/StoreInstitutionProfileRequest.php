<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstitutionProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id'    => 'required|exists:institutions,id|unique:institution_profiles,institution_id',
            'facilities'        => 'nullable|string',
            'infrastructure'    => 'nullable|string',
            'achievements'      => 'nullable|string',
            'accreditations'    => 'nullable|string',
            'has_hostel'        => 'nullable|boolean',
            'has_transportation'=> 'nullable|boolean',
            'has_library'       => 'nullable|boolean',
            'has_lab'           => 'nullable|boolean',
            'has_cafeteria'     => 'nullable|boolean',
            'has_sports'        => 'nullable|boolean',
            'has_scholarship'   => 'nullable|boolean',
            'facebook_url'      => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'linkedin_url'      => 'nullable|url|max:255',
            'youtube_url'       => 'nullable|url|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'institution_id.unique' => 'This institution already has a profile.',
        ];
    }
}
