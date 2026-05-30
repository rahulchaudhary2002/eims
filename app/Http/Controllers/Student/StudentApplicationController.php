<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentApplicationRequest;
use App\Models\Application;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $studentId    = $request->user('student')->id;
        $applications = Application::where('student_id', $studentId)
            ->with(['institution', 'institutionProgram.program', 'scholarship'])
            ->latest()
            ->paginate(12);

        return view('student.applications.index', compact('applications'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::active()->orderBy('name')->get();
        $scholarships = Scholarship::where('status', 'active')->orderBy('title')->get();

        $selectedInstitution  = null;
        $selectedProgram      = null;
        $selectedScholarship  = null;

        if ($request->has('institution')) {
            $selectedInstitution = Institution::where('slug', $request->institution)->first();
        }
        if ($request->has('program') && $selectedInstitution) {
            $selectedProgram = InstitutionProgram::where('slug', $request->program)
                ->where('institution_id', $selectedInstitution->id)
                ->first();
        }
        if ($request->has('scholarship')) {
            $selectedScholarship = Scholarship::where('slug', $request->scholarship)->first();
        }

        return view('student.applications.create', compact(
            'institutions', 'scholarships',
            'selectedInstitution', 'selectedProgram', 'selectedScholarship'
        ));
    }

    public function store(StoreStudentApplicationRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id']        = $studentId;
        $data['application_number'] = 'APP-' . strtoupper(Str::random(8));
        $data['status']            = 'submitted';
        $data['source']            = $data['source'] ?? 'direct';
        $data['submitted_at']      = now();

        Application::create($data);

        return redirect()->route('student.applications.index')
            ->with('success', 'Application submitted successfully.');
    }

    public function show(Request $request, Application $application): View
    {
        abort_if($application->student_id !== $request->user('student')->id, 403);

        $application->load(['institution', 'institutionProgram.program', 'scholarship', 'statusLogs', 'admission']);

        return view('student.applications.show', compact('application'));
    }

    public function cancel(Request $request, Application $application): RedirectResponse
    {
        abort_if($application->student_id !== $request->user('student')->id, 403);
        abort_if(!in_array($application->status, ['draft', 'submitted']), 403);

        $application->update(['status' => 'withdrawn']);

        return back()->with('success', 'Application withdrawn.');
    }
}
