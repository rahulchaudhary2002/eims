<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInstitutionProgramSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionProgramRule = Rule::exists('institution_programs', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $institutionProgramRule->where('institution_id', (int) session('current_institution_id', 0));
        }

        return [
            'institution_program_id' => ['required', $institutionProgramRule],
            'subject_name'           => ['required', 'string', 'max:255'],
            'is_optional'            => ['boolean'],
        ];
    }
}
