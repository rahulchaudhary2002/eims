<?php

namespace App\Http\Requests\Admin;

use App\Models\CounselingSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCounselingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $institutionRule = Rule::exists('institutions', 'id');

        if (! auth('web')->user()?->is_super_admin) {
            $scope = (int) session('current_institution_id', 0);
            $institutionRule->where('id', $scope);
        }

        return [
            'student_id'      => ['required', Rule::exists('students', 'id')],
            'institution_id'  => ['nullable', $institutionRule],
            'counselor_id'    => ['nullable', Rule::exists('users', 'id')],
            'mode'            => ['required', Rule::in(array_keys(CounselingSession::MODES))],
            'scheduled_at'    => ['required', 'date'],
            'status'          => ['required', Rule::in(array_keys(CounselingSession::STATUSES))],
            'student_message' => ['nullable', 'string'],
            'counselor_notes' => ['nullable', 'string'],
        ];
    }
}
