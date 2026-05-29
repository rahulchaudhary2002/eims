<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionProgram;
use App\Models\Program;

class InstitutionProgramController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionProgram::class;
        $this->routeBase = 'programs';
        $this->title = 'Program';
        $this->selectOptions = ['status' => InstitutionProgram::STATUSES];
        $this->fields = [
            'program_id' => ['label' => 'Global Program', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:programs,id']],
            'title' => ['label' => 'Title', 'rules' => ['nullable', 'string', 'max:255']],
            'admission_fee' => ['label' => 'Admission Fee', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'monthly_fee' => ['label' => 'Monthly Fee', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'semester_fee' => ['label' => 'Semester Fee', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'annual_fee' => ['label' => 'Annual Fee', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'total_fee' => ['label' => 'Total Fee', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'duration_months' => ['label' => 'Duration Months', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:1']],
            'total_seats' => ['label' => 'Total Seats', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
            'available_seats' => ['label' => 'Available Seats', 'type' => 'number', 'rules' => ['nullable', 'integer', 'min:0']],
            'minimum_gpa' => ['label' => 'Minimum GPA', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'minimum_percentage' => ['label' => 'Minimum Percentage', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0', 'max:100']],
            'admission_start_date' => ['label' => 'Admission Start Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'admission_end_date' => ['label' => 'Admission End Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
        ];
    }

    protected function selectOptions(): array
    {
        return array_merge($this->selectOptions, [
            'program_id' => Program::orderBy('name')->pluck('name', 'id')->all(),
        ]);
    }
}
