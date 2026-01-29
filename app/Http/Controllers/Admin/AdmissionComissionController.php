<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstitutionAdmissionComission;
use Illuminate\Http\Request;

class AdmissionComissionController extends Controller
{
    public function index()
    {
        $comissions = InstitutionAdmissionComission::latest()->paginate(10);
        return view('admin.modules.admission_comission.index', compact('comissions'));
    }

    public function markAsPaid(InstitutionAdmissionComission $comission)
    {
        $comission->update(['is_paid' => true]);
        return redirect()->route('admin.admission.comission.index')->with('success', 'Comission marked as paid successfully.');
    }
}
