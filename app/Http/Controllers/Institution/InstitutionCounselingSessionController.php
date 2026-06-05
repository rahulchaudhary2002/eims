<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InstitutionCounselingSessionController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = CounselingSession::class;
        $this->routeBase = 'counseling-sessions';
        $this->title = 'Counseling Session';
        $this->relationships = ['student', 'counselor'];
        $this->readOnlyFields = ['student_id'];
        $this->selectOptions = ['mode' => CounselingSession::MODES, 'status' => CounselingSession::STATUSES];
        $this->fields = [
            'student_id' => ['label' => 'Student', 'type' => 'number', 'rules' => ['nullable', 'integer', 'exists:students,id']],
            'counselor_id' => ['label' => 'Counselor', 'type' => 'select', 'rules' => ['nullable', 'integer', 'exists:users,id']],
            'mode' => ['label' => 'Mode', 'type' => 'select', 'rules' => ['required', 'string']],
            'scheduled_at' => ['label' => 'Scheduled At', 'type' => 'datetime-local', 'rules' => ['required', 'date']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'student_message' => ['label' => 'Student Message', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
            'counselor_notes' => ['label' => 'Counselor Notes', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        if (! empty($data['counselor_id'])) {
            abort_unless(User::whereKey($data['counselor_id'])->whereHas('activeInstitutions', fn ($query) => $query->where('institutions.id', $this->activeInstitutionId()))->exists(), 403);
        }

        return parent::forceInstitutionScope($data, $record);
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'counselor_id' => $this->assignedInstitutionUsers()->pluck('name', 'id')->all(),
        ]);
    }
}
