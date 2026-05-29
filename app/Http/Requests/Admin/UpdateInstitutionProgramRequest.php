<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionProgram = $this->route('institutionProgram');

        return [
            'institution_id'       => ['required', 'exists:institutions,id'],
            'program_id'           => ['required', 'exists:programs,id', 'unique:institution_programs,program_id,' . $institutionProgram->id . ',id,institution_id,' . $this->input('institution_id')],
            'title'                => ['nullable', 'string', 'max:255'],
            'admission_fee'        => ['nullable', 'numeric', 'min:0'],
            'monthly_fee'          => ['nullable', 'numeric', 'min:0'],
            'semester_fee'         => ['nullable', 'numeric', 'min:0'],
            'annual_fee'           => ['nullable', 'numeric', 'min:0'],
            'total_fee'            => ['nullable', 'numeric', 'min:0'],
            'duration_months'      => ['nullable', 'integer', 'min:1', 'max:600'],
            'total_seats'          => ['nullable', 'integer', 'min:0'],
            'available_seats'      => ['nullable', 'integer', 'min:0'],
            'minimum_gpa'          => ['nullable', 'numeric', 'min:0', 'max:4'],
            'minimum_percentage'   => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admission_start_date' => ['nullable', 'date'],
            'admission_end_date'   => ['nullable', 'date', 'after_or_equal:admission_start_date'],
            'status'               => ['required', 'in:open,closed,upcoming,suspended'],
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.unique' => 'This program is already offered by the selected institution.',
        ];
    }
}
