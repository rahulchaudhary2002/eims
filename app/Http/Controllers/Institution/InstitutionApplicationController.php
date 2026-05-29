<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Application;

class InstitutionApplicationController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Application::class;
        $this->routeBase = 'applications';
        $this->title = 'Application';
        $this->readOnlyFields = ['application_number', 'student_id', 'institution_id', 'institution_program_id', 'scholarship_id', 'source', 'admin_remarks'];
        $this->selectOptions = ['status' => Application::STATUSES];
        $this->relationships = ['student', 'institutionProgram', 'scholarship'];
        $this->fields = [
            'application_number' => ['label' => 'Application Number'],
            'student_id' => ['label' => 'Student'],
            'institution_program_id' => ['label' => 'Program'],
            'scholarship_id' => ['label' => 'Scholarship'],
            'source' => ['label' => 'Source'],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'student_message' => ['label' => 'Student Message', 'type' => 'textarea'],
            'institution_remarks' => ['label' => 'Institution Remarks', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }
}
