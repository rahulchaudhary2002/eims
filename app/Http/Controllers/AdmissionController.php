<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function apply(Admission $admission)
    {
        return view('modules.admission.apply', compact('admission'));
    }

    public function storeApplication(Request $request, Admission $admission)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
            'course_id' => $admission->isForCourse() ? 'required|exists:courses,id' : 'nullable',
            'grade'     => $admission->isForGrade() ? 'required|string' : 'nullable',
        ]);

        AdmissionApplication::create([
            'admission_id' => $admission->id,
            'user_id'      => Auth::id(),
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'status'       => 'pending',
            'notes'        => $request->notes,
            'course_id'    => $request->course_id,
            'grade'        => $request->grade,
        ]);

        return redirect()->route('admission.show', $admission->slug)
            ->with('success', 'Your application has been submitted successfully.');
    }

    public function myApplications()
    {
        $applications = AdmissionApplication::where('user_id', Auth::id())
            ->with('admission')
            ->latest()
            ->paginate(10);

        return view('modules.admission.my_applications', compact('applications'));
    }
}
