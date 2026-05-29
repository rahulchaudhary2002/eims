<?php

namespace App\Http\Requests\Admin;

use App\Models\ScholarshipApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScholarshipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $scholarshipRule = Rule::exists('scholarships', 'id');
        $applicationRule = Rule::exists('applications', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $scholarshipRule->where('institution_id', $scope);
            $applicationRule->where('institution_id', $scope);
        }

        return [
            'scholarship_id'  => ['required', $scholarshipRule],
            'student_id'      => ['required', Rule::exists('students', 'id')],
            'application_id'  => ['nullable', $applicationRule],
            'status'          => ['required', Rule::in(array_keys(ScholarshipApplication::STATUSES))],
            'approved_amount' => ['nullable', 'numeric', 'min:0'],
            'remarks'         => ['nullable', 'string'],
        ];
    }
}
