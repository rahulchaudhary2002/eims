<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\ApplicationRequest;
use App\Models\Application;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function create(\Illuminate\Http\Request $request)
    {
        $student = Auth::guard('student')->user();

        $institutions = Institution::active()->orderBy('name')->get(['id', 'name', 'slug']);
        $programs     = InstitutionProgram::where('status', 'open')
            ->with(['institution', 'program'])
            ->whereHas('institution', fn($q) => $q->active())
            ->get();

        $scholarships = Scholarship::where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with('institution')
            ->get(['id', 'title', 'institution_id', 'institution_program_id']);

        $selectedInstitutionId = null;
        $selectedProgramId     = null;
        $selectedScholarshipId = null;

        if ($slug = $request->input('institution')) {
            $selectedInstitutionId = Institution::where('slug', $slug)->value('id');
        }

        if ($slug = $request->input('program')) {
            $selectedProgramId = InstitutionProgram::where('slug', $slug)->value('id');
        }

        if ($slug = $request->input('scholarship')) {
            $selectedScholarshipId = Scholarship::where('slug', $slug)->value('id');
        }

        return view('website.applications.create', compact(
            'student', 'institutions', 'programs', 'scholarships',
            'selectedInstitutionId', 'selectedProgramId', 'selectedScholarshipId'
        ));
    }

    public function store(ApplicationRequest $request)
    {
        $student = Auth::guard('student')->user();
        $data    = $request->validated();

        $alreadyApplied = Application::where('student_id', $student->id)
            ->where('institution_program_id', $data['institution_program_id'])
            ->whereNotIn('status', ['withdrawn', 'rejected'])
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already submitted an application for this program.');
        }

        Application::create([
            'application_number'    => 'APP-' . now()->year . '-' . strtoupper(Str::random(6)),
            'student_id'            => $student->id,
            'institution_id'        => $data['institution_id'],
            'institution_program_id' => $data['institution_program_id'],
            'scholarship_id'        => $data['scholarship_id'] ?? null,
            'source'                => $data['source'] ?? 'direct',
            'student_message'       => $data['student_message'] ?? null,
            'status'                => 'submitted',
            'submitted_at'          => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Application submitted successfully!');
    }
}
