<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Inquiry;
use App\Models\InstitutionProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InstitutionInquiryController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Inquiry::class;
        $this->routeBase = 'inquiries';
        $this->title = 'Inquiry';
        $this->relationships = ['institutionProgram', 'assignedTo'];
        $this->selectOptions = [
            'source' => Inquiry::SOURCES,
            'status' => Inquiry::STATUSES,
        ];
        $this->fields = [
            'institution_program_id' => ['label' => 'Program', 'type' => 'select', 'rules' => ['nullable', 'integer', 'exists:institution_programs,id']],
            'name' => ['label' => 'Name', 'rules' => ['required', 'string', 'max:255']],
            'email' => ['label' => 'Email', 'type' => 'email', 'rules' => ['required', 'email', 'max:255']],
            'phone' => ['label' => 'Phone', 'rules' => ['nullable', 'string', 'max:50']],
            'message' => ['label' => 'Message', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
            'source' => ['label' => 'Source', 'type' => 'select', 'rules' => ['nullable', 'string']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'assigned_to' => ['label' => 'Assigned To', 'type' => 'select', 'rules' => ['nullable', 'integer', 'exists:users,id']],
            'last_contacted_at' => ['label' => 'Last Contacted At', 'type' => 'datetime-local', 'rules' => ['nullable', 'date']],
        ];
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        if (! empty($data['institution_program_id'])) {
            abort_unless(InstitutionProgram::where('institution_id', $this->activeInstitutionId())->whereKey($data['institution_program_id'])->exists(), 403);
        }

        if (! empty($data['assigned_to'])) {
            abort_unless(User::whereKey($data['assigned_to'])->whereHas('activeInstitutions', fn ($query) => $query->where('institutions.id', $this->activeInstitutionId()))->exists(), 403);
        }

        return parent::forceInstitutionScope($data, $record);
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'institution_program_id' => InstitutionProgram::with('program')
                ->where('institution_id', $this->activeInstitutionId())
                ->orderBy('title')
                ->get()
                ->mapWithKeys(fn (InstitutionProgram $program) => [$program->id => $program->display_name])
                ->all(),
            'assigned_to' => $this->assignedInstitutionUsers()->pluck('name', 'id')->all(),
        ]);
    }
}
