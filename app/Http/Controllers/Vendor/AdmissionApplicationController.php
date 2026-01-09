<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;

class AdmissionApplicationController extends Controller
{
    public function index(Admission $admission)
    {
        $institution = session('current_institution');

        if ($institution->id !== $admission->institution_id) {
            abort(404);
        }

        $applications = AdmissionApplication::latest()->paginate(10);
        return view('vendor.modules.admission.application.index', compact('admission', 'applications'));
    }

    public function show(Admission $admission, AdmissionApplication $application)
    {
        $institution = session('current_institution');

        if ($institution->id !== $admission->institution_id) {
            abort(404);
        }

        if ($application->admission_id !== $admission->id) {
            abort(404);
        }

        return view('vendor.modules.admission.application.show', compact('admission', 'application'));
    }
}
