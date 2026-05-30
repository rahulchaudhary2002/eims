<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentInquiryRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentInquiryController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $inquiries = Inquiry::where('student_id', $studentId)
            ->with(['institution', 'institutionProgram.program'])
            ->latest()
            ->paginate(12);

        return view('student.inquiries.index', compact('inquiries'));
    }

    public function create(Request $request): View
    {
        $student      = $request->user('student');
        $institutions = Institution::active()->orderBy('name')->get();

        $selectedInstitution = null;
        $selectedProgram     = null;

        if ($request->has('institution')) {
            $selectedInstitution = Institution::where('slug', $request->institution)->first();
        }
        if ($request->has('program') && $selectedInstitution) {
            $selectedProgram = InstitutionProgram::where('slug', $request->program)
                ->where('institution_id', $selectedInstitution->id)
                ->first();
        }

        return view('student.inquiries.create', compact(
            'student', 'institutions', 'selectedInstitution', 'selectedProgram'
        ));
    }

    public function store(StoreStudentInquiryRequest $request): RedirectResponse
    {
        $student = $request->user('student');
        $data    = $request->validated();
        $data['student_id'] = $student->id;
        $data['status']     = 'new';

        $data['name']  = $data['name']  ?? $student->name;
        $data['email'] = $data['email'] ?? $student->email;
        $data['phone'] = $data['phone'] ?? $student->phone;

        Inquiry::create($data);

        return redirect()->route('student.inquiries.index')
            ->with('success', 'Inquiry submitted successfully.');
    }

    public function show(Request $request, Inquiry $inquiry): View
    {
        abort_if($inquiry->student_id !== $request->user('student')->id, 403);

        $inquiry->load(['institution', 'institutionProgram.program']);

        return view('student.inquiries.show', compact('inquiry'));
    }
}
