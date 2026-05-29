<?php

namespace App\Http\Requests\Admin;

use App\Models\Admission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $admissionId = $this->route('admission')?->id;
        $applicationRule = Rule::exists('applications', 'id');
        $institutionRule = Rule::exists('institutions', 'id');
        $institutionProgramRule = Rule::exists('institution_programs', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $applicationRule->where('institution_id', $scope);
            $institutionRule->where('id', $scope);
            $institutionProgramRule->where('institution_id', $scope);
        }

        return [
            'application_id'         => ['required', $applicationRule, Rule::unique('admissions', 'application_id')->ignore($admissionId)],
            'student_id'             => ['required', 'exists:students,id'],
            'institution_id'         => ['required', $institutionRule],
            'institution_program_id' => ['required', $institutionProgramRule],
            'admission_number'       => ['nullable', 'string', 'max:50', Rule::unique('admissions', 'admission_number')->ignore($admissionId)],
            'admission_date'         => ['required', 'date'],
            'paid_amount'            => ['nullable', 'numeric', 'min:0'],
            'payment_proof'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
            'verification_status'    => ['required', Rule::in(array_keys(Admission::VERIFICATION_STATUSES))],
            'verified_by'            => ['nullable', 'exists:users,id'],
            'verified_at'            => ['nullable', 'date'],
            'remarks'                => ['nullable', 'string'],
        ];
    }
}
