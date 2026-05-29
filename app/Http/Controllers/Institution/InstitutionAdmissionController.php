<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Admission;

class InstitutionAdmissionController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Admission::class;
        $this->routeBase = 'admissions';
        $this->title = 'Admission';
        $this->fileFields = ['payment_proof' => 'admissions'];
        $this->selectOptions = ['verification_status' => Admission::VERIFICATION_STATUSES];
        $this->readOnlyFields = ['application_id', 'student_id', 'institution_id', 'institution_program_id', 'admission_number', 'verified_by', 'verified_at'];
        $this->relationships = ['application', 'student', 'institutionProgram'];
        $this->fields = [
            'admission_number' => ['label' => 'Admission Number'],
            'admission_date' => ['label' => 'Admission Date', 'type' => 'date', 'rules' => ['nullable', 'date']],
            'paid_amount' => ['label' => 'Paid Amount', 'type' => 'number', 'rules' => ['nullable', 'numeric', 'min:0']],
            'payment_proof' => ['label' => 'Payment Proof', 'type' => 'file', 'rules' => ['nullable', 'file', 'max:10240']],
            'verification_status' => ['label' => 'Verification Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }
}
