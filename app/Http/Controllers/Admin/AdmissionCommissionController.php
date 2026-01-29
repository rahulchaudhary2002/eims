<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionAdmissionCommission;
use Illuminate\Http\Request;

class AdmissionCommissionController extends Controller
{
    public function index()
    {
        $commissions = InstitutionAdmissionCommission::latest()->paginate(10);
        return view('admin.modules.admission_commission.index', compact('commissions'));
    }

    public function markAsPaid(InstitutionAdmissionCommission $commission)
    {
        $commission->update(['is_paid' => true]);
        return redirect()->route('admin.admission.commission.index')->with('success', 'Commission marked as paid successfully.');
    }
}
