<?php

namespace App\Http\Requests\Student;

use App\Models\CounselingSession;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentCounselingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('student')->check();
    }

    public function rules(): array
    {
        return [
            'institution_id' => ['nullable', 'integer', 'exists:institutions,id'],
            'mode'           => ['required', 'in:' . implode(',', array_keys(CounselingSession::MODES))],
            'scheduled_at'   => ['required', 'date', 'after:now'],
            'student_message'=> ['nullable', 'string', 'max:2000'],
        ];
    }
}
