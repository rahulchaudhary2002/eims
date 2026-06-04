<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentAcademicRecordRequest;
use App\Http\Requests\Student\UpdateStudentAcademicRecordRequest;
use App\Models\StudentAcademicRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentAcademicRecordController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $records   = StudentAcademicRecord::where('student_id', $studentId)
            ->orderBy('passed_year', 'desc')
            ->get();

        return view('student.academic-records.index', compact('records'));
    }

    public function create(): View
    {
        return view('student.academic-records.create');
    }

    public function store(StoreStudentAcademicRecordRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id'] = $studentId;

        if ($request->hasFile('transcript_file')) {
            $data['transcript_file'] = $request->file('transcript_file')
                ->store("students/{$studentId}/academic", 'public');
        }
        if ($request->hasFile('character_certificate_file')) {
            $data['character_certificate_file'] = $request->file('character_certificate_file')
                ->store("students/{$studentId}/academic", 'public');
        }

        StudentAcademicRecord::create($data);

        return redirect()->route('student.academic-records.index')
            ->with('success', 'Academic record added successfully.');
    }

    public function show(Request $request, StudentAcademicRecord $academicRecord): View
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        return view('student.academic-records.show', compact('academicRecord'));
    }

    public function edit(Request $request, StudentAcademicRecord $academicRecord): View
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        return view('student.academic-records.edit', compact('academicRecord'));
    }

    public function update(UpdateStudentAcademicRecordRequest $request, StudentAcademicRecord $academicRecord): RedirectResponse
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        $studentId = $request->user('student')->id;
        $data      = $request->validated();

        if ($request->hasFile('transcript_file')) {
            if ($academicRecord->transcript_file) {
                Storage::disk('public')->delete($academicRecord->transcript_file);
            }
            $data['transcript_file'] = $request->file('transcript_file')
                ->store("students/{$studentId}/academic", 'public');
        }
        if ($request->hasFile('character_certificate_file')) {
            if ($academicRecord->character_certificate_file) {
                Storage::disk('public')->delete($academicRecord->character_certificate_file);
            }
            $data['character_certificate_file'] = $request->file('character_certificate_file')
                ->store("students/{$studentId}/academic", 'public');
        }

        $academicRecord->update($data);

        return redirect()->route('student.academic-records.index')
            ->with('success', 'Academic record updated successfully.');
    }

    public function destroy(Request $request, StudentAcademicRecord $academicRecord): RedirectResponse
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        if ($academicRecord->transcript_file) {
            Storage::disk('public')->delete($academicRecord->transcript_file);
        }
        if ($academicRecord->character_certificate_file) {
            Storage::disk('public')->delete($academicRecord->character_certificate_file);
        }

        $academicRecord->delete();

        return redirect()->route('student.academic-records.index')
            ->with('success', 'Academic record deleted.');
    }
}
