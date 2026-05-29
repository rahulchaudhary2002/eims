<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Model;

class InstitutionScholarshipController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Scholarship::class;
        $this->routeBase = 'scholarships';
        $this->title = 'Scholarship';
        $this->selectOptions = [
            'type' => Scholarship::TYPES,
            'benefit_type' => Scholarship::BENEFIT_TYPES,
            'status' => Scholarship::STATUSES,
        ];
        $this->fields = [
            'institution_program_id' => ['label' => 'Institution Program', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:institution_programs,id']],
            'type' => ['label' => 'Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'title' => ['label' => 'Title', 'rules' => ['required', 'string', 'max:255']],
            'description' => ['label' => 'Description', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
            'minimum_gpa' => ['label' => 'Minimum GPA', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'minimum_percentage' => ['label' => 'Minimum Percentage', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0', 'max:100']],
            'benefit_type' => ['label' => 'Benefit Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'benefit_value' => ['label' => 'Benefit Value', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'total_slots' => ['label' => 'Total Slots', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
            'used_slots' => ['label' => 'Used Slots', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
            'start_date' => ['label' => 'Start Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'end_date' => ['label' => 'End Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
        ];
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(InstitutionProgram::where('institution_id', $this->activeInstitutionId())->whereKey($data['institution_program_id'] ?? null)->exists(), 403);

        return parent::forceInstitutionScope($data, $record);
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'institution_program_id' => InstitutionProgram::where('institution_id', $this->activeInstitutionId())->orderBy('title')->pluck('title', 'id')->all(),
        ]);
    }
}
