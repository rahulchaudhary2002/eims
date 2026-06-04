<?php

namespace App\Http\Requests\Student;

use App\Models\StudentRewardClaim;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRewardClaimRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'application_id'               => [
                'required',
                Rule::exists('applications', 'id')->where(fn ($query) => $query
                    ->where('student_id', $this->user('student')?->id)),
                Rule::unique('student_reward_claims', 'application_id')
                    ->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
            'claimed_reward_amount'        => ['nullable', 'numeric', 'min:0'],
            'payment_method'               => ['required', 'in:' . implode(',', array_keys(StudentRewardClaim::PAYMENT_METHODS))],
            'student_note'                 => ['nullable', 'string', 'max:2000'],
            'documents'                    => ['required', 'array', 'min:1'],
            'documents.*.document_type'    => ['required', 'in:' . implode(',', array_keys(StudentRewardClaim::DOCUMENT_TYPES))],
            'documents.*.file'             => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
