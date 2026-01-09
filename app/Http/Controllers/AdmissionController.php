<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        $admissions = Admission::latest()->paginate(12);
        return view('modules.admission.index', compact('admissions'));
    }

    public function show(Admission $admission)
    {
        return view('modules.admission.show', compact('admission'));
    }
}
