<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Website\ApplicationRequest;
use App\Models\Application;
use App\Models\Institution;
use App\Models\ConsultancyService;
use App\Models\InstitutionCertification;
use App\Models\InstitutionCourse;
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

        $applicables = [
            \App\Models\InstitutionProgram::class => InstitutionProgram::where('status', 'open')
                ->with(['institution', 'program'])
                ->whereHas('institution', fn($q) => $q->active())
                ->get(),
            \App\Models\InstitutionCourse::class => InstitutionCourse::where('is_active', true)
                ->with('institution')
                ->whereHas('institution', fn($q) => $q->active())
                ->get(),
            \App\Models\InstitutionCertification::class => InstitutionCertification::where('is_active', true)
                ->with('institution')
                ->whereHas('institution', fn($q) => $q->active())
                ->get(),
            \App\Models\ConsultancyService::class => ConsultancyService::where('is_active', true)
                ->with('institution')
                ->whereHas('institution', fn($q) => $q->active())
                ->get(),
        ];

        $scholarships = Scholarship::where('status', 'active')
            ->whereDate('end_date', '>=', now())
            ->with('institution')
            ->get(['id', 'title', 'institution_id']);

        $selectedInstitutionId  = null;
        $selectedApplicableType = null;
        $selectedApplicableId   = null;
        $selectedScholarshipId  = null;

        if ($slug = $request->input('institution')) {
            $selectedInstitutionId = Institution::where('slug', $slug)->value('id');
        }

        if ($slug = $request->input('program')) {
            $prog = InstitutionProgram::where('slug', $slug)->first();
            if ($prog) {
                $selectedApplicableType = InstitutionProgram::class;
                $selectedApplicableId   = $prog->id;
                $selectedInstitutionId  ??= $prog->institution_id;
            }
        }
        if ($slug = $request->input('course')) {
            $course = InstitutionCourse::where('slug', $slug)->first();
            if ($course) {
                $selectedApplicableType = InstitutionCourse::class;
                $selectedApplicableId   = $course->id;
                $selectedInstitutionId  ??= $course->institution_id;
            }
        }
        if ($slug = $request->input('certification')) {
            $cert = InstitutionCertification::where('slug', $slug)->first();
            if ($cert) {
                $selectedApplicableType = InstitutionCertification::class;
                $selectedApplicableId   = $cert->id;
                $selectedInstitutionId  ??= $cert->institution_id;
            }
        }
        if ($id = $request->input('service')) {
            $svc = ConsultancyService::find($id);
            if ($svc) {
                $selectedApplicableType = ConsultancyService::class;
                $selectedApplicableId   = $svc->id;
                $selectedInstitutionId  ??= $svc->institution_id;
            }
        }

        if ($slug = $request->input('scholarship')) {
            $selectedScholarshipId = Scholarship::where('slug', $slug)->value('id');
        }

        return view('website.applications.create', compact(
            'student', 'institutions', 'applicables', 'scholarships',
            'selectedInstitutionId', 'selectedApplicableType', 'selectedApplicableId', 'selectedScholarshipId'
        ));
    }

    public function store(ApplicationRequest $request)
    {
        $student = Auth::guard('student')->user();
        $data    = $request->validated();

        $alreadyApplied = Application::where('student_id', $student->id)
            ->where('applicable_type', $data['applicable_type'])
            ->where('applicable_id', $data['applicable_id'])
            ->whereNotIn('status', ['withdrawn', 'rejected'])
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already submitted an application for this program.');
        }

        Application::create([
            'application_number' => 'APP-' . now()->year . '-' . strtoupper(Str::random(6)),
            'student_id'         => $student->id,
            'institution_id'     => $data['institution_id'],
            'applicable_type'    => $data['applicable_type'],
            'applicable_id'      => $data['applicable_id'],
            'scholarship_id'     => $data['scholarship_id'] ?? null,
            'source'             => $data['source'] ?? 'direct',
            'student_message'    => $data['student_message'] ?? null,
            'status'             => 'submitted',
            'submitted_at'       => now(),
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Application submitted successfully!');
    }
}
