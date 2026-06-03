<?php

namespace App\Http\Requests\Student;

use App\Models\StudentRewardClaim;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRewardClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id'               => ['required', 'exists:institutions,id'],
            'institution_program_id'       => ['nullable', 'exists:institution_programs,id'],
            'application_id'               => ['nullable', 'exists:applications,id'],
            'admission_date'               => ['required', 'date', 'before_or_equal:today'],
            'admission_number'             => ['nullable', 'string', 'max:100'],
            'intake'                       => ['nullable', 'string', 'max:100'],
            'claimed_reward_amount'        => ['nullable', 'numeric', 'min:0'],
            'payment_method'               => ['nullable', 'in:' . implode(',', array_keys(StudentRewardClaim::PAYMENT_METHODS))],
            'student_note'                 => ['nullable', 'string', 'max:2000'],
            'documents'                    => ['required', 'array', 'min:1'],
            'documents.*.document_type'    => ['required', 'in:' . implode(',', array_keys(StudentRewardClaim::DOCUMENT_TYPES))],
            'documents.*.file'             => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
