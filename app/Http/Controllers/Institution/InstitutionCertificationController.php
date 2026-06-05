<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionCertification;

class InstitutionCertificationController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionCertification::class;
        $this->routeBase  = 'certifications';
        $this->title      = 'Certification';
        $this->fields     = [
            'title'          => ['label' => 'Title',          'rules' => ['required', 'string', 'max:255']],
            'fee'            => ['label' => 'Fee',             'type' => 'number',   'rules' => ['nullable', 'numeric', 'min:0']],
            'duration_hours' => ['label' => 'Duration (hrs)', 'type' => 'number',   'rules' => ['nullable', 'integer', 'min:1']],
            'is_active'      => ['label' => 'Active',          'type' => 'checkbox', 'rules' => ['nullable']],
            'description'    => ['label' => 'Description',    'type' => 'ckeditor', 'rules' => ['nullable', 'string']],
        ];
    }


}
