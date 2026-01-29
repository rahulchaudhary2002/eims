<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\InstitutionAdmissionCommission;
use Illuminate\Http\Request;

class AdmissionCommissionController extends Controller
{
    public function index()
    {
        $institution = session('current_institution');

        $commissions = InstitutionAdmissionCommission::where('institution_id', $institution->id)->latest()->paginate(10);
        return view('vendor.modules.admission_commission.index', compact('commissions'));
    }

    public function pay(InstitutionAdmissionCommission $commission)
    {
        $institution = session('current_institution');

        if ($commission->institution_id !== $institution->id) {
            abort(403);
        }

        $commission->is_paid = true;
        $commission->save();

        return redirect()->route('vendor.admission.commission.index')
            ->with('success', 'Commission marked as paid successfully.');
    }
}
