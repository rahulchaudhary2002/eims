<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\CommissionInvoice;

class InstitutionCommissionInvoiceController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = CommissionInvoice::class;
        $this->routeBase = 'commission-invoices';
        $this->title = 'Commission Invoice';
        $this->relationships = ['admission', 'payments'];
        $this->fields = [
            'invoice_number' => ['label' => 'Invoice Number'],
            'admission_id' => ['label' => 'Admission'],
            'admission_paid_amount' => ['label' => 'Admission Paid Amount'],
            'commission_type' => ['label' => 'Commission Type'],
            'commission_value' => ['label' => 'Commission Value'],
            'commission_amount' => ['label' => 'Commission Amount'],
            'student_cashback_amount' => ['label' => 'Student Cashback Amount'],
            'status' => ['label' => 'Status'],
            'invoice_date' => ['label' => 'Invoice Date'],
            'due_date' => ['label' => 'Due Date'],
            'paid_at' => ['label' => 'Paid At'],
        ];
    }
}
