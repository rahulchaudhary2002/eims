<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\AdmissionApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        // Check if an application with the same admission_id, user_id, course_id, and grade already exists
        $existing = AdmissionApplication::where('admission_id', $admission->id)
            ->where('user_id', Auth::id())
            ->where('course_id', $request->course_id)
            ->where('grade', $request->grade)
            ->first();

        if ($existing) {
            return redirect()->route('admission.show', $admission->slug)
                ->with('error', 'You have already submitted an application for this admission with the same course and grade.');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email',
            'phone'     => 'required|string|max:20',
            'course_id' => $admission->isForCourse() ? 'required|exists:courses,id' : 'nullable',
            'grade'     => $admission->isForGrade() ? 'required|string' : 'nullable',
            'notes'     => 'nullable|string|max:1000',
            'academic_documents'   => 'required|array|min:1',
            'academic_documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documents = [];

        if ($request->hasFile('academic_documents')) {
            foreach ($request->file('academic_documents') as $file) {
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('academic_documents', $filename, 'public');
                $documents[] = $path;
            }
        }

        AdmissionApplication::create([
            'application_uuid' => (string) Str::uuid(),
            'admission_id' => $admission->id,
            'user_id'      => Auth::id(),
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'status'       => 'pending',
            'notes'        => $request->notes,
            'course_id'    => $request->course_id,
            'grade'        => $request->grade,
            'academic_documents' => json_encode($documents),
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
