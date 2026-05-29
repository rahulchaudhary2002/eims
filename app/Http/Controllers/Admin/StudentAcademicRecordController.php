<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentAcademicRecordRequest;
use App\Http\Requests\Admin\UpdateStudentAcademicRecordRequest;
use App\Models\Student;
use App\Models\StudentAcademicRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentAcademicRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentAcademicRecord::with('student');

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }
        if ($board = $request->input('board')) {
            $query->where('board', $board);
        }
        if ($passedYear = $request->input('passed_year')) {
            $query->where('passed_year', (int) $passedYear);
        }
        if ($request->input('is_verified') !== null && $request->input('is_verified') !== '') {
            $query->where('is_verified', (bool) $request->input('is_verified'));
        }

        $records  = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $levels   = StudentAcademicRecord::LEVELS;
        $boards   = StudentAcademicRecord::BOARDS;

        return view('admin.modules.student-academic-records.index', compact(
            'records', 'students', 'levels', 'boards'
        ));
    }

    public function create(): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $levels   = StudentAcademicRecord::LEVELS;
        $boards   = StudentAcademicRecord::BOARDS;

        return view('admin.modules.student-academic-records.create', compact('students', 'levels', 'boards'));
    }

    public function store(StoreStudentAcademicRecordRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_verified'] = (bool) ($data['is_verified'] ?? false);

        if ($request->hasFile('transcript_file')) {
            $data['transcript_file'] = $request->file('transcript_file')
                ->store('student-academic-records/transcripts', 'public');
        }

        if ($request->hasFile('character_certificate_file')) {
            $data['character_certificate_file'] = $request->file('character_certificate_file')
                ->store('student-academic-records/certificates', 'public');
        }

        $record = StudentAcademicRecord::create($data);

        return redirect()->route('admin.student-academic-records.show', $record)
            ->with('success', 'Academic record added successfully.');
    }

    public function show(StudentAcademicRecord $studentAcademicRecord): View
    {
        $studentAcademicRecord->load('student');
        $levels = StudentAcademicRecord::LEVELS;
        $boards = StudentAcademicRecord::BOARDS;

        return view('admin.modules.student-academic-records.show', compact('studentAcademicRecord', 'levels', 'boards'));
    }

    public function edit(StudentAcademicRecord $studentAcademicRecord): View
    {
        $studentAcademicRecord->load('student');
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $levels   = StudentAcademicRecord::LEVELS;
        $boards   = StudentAcademicRecord::BOARDS;

        return view('admin.modules.student-academic-records.edit', compact(
            'studentAcademicRecord', 'students', 'levels', 'boards'
        ));
    }

    public function update(UpdateStudentAcademicRecordRequest $request, StudentAcademicRecord $studentAcademicRecord): RedirectResponse
    {
        $data = $request->validated();
        $data['is_verified'] = (bool) ($data['is_verified'] ?? false);

        if ($request->hasFile('transcript_file')) {
            if ($studentAcademicRecord->transcript_file) {
                Storage::disk('public')->delete($studentAcademicRecord->transcript_file);
            }
            $data['transcript_file'] = $request->file('transcript_file')
                ->store('student-academic-records/transcripts', 'public');
        } else {
            unset($data['transcript_file']);
        }

        if ($request->hasFile('character_certificate_file')) {
            if ($studentAcademicRecord->character_certificate_file) {
                Storage::disk('public')->delete($studentAcademicRecord->character_certificate_file);
            }
            $data['character_certificate_file'] = $request->file('character_certificate_file')
                ->store('student-academic-records/certificates', 'public');
        } else {
            unset($data['character_certificate_file']);
        }

        $studentAcademicRecord->update($data);

        return redirect()->route('admin.student-academic-records.show', $studentAcademicRecord)
            ->with('success', 'Academic record updated successfully.');
    }

    public function destroy(StudentAcademicRecord $studentAcademicRecord): RedirectResponse
    {
        if ($studentAcademicRecord->transcript_file) {
            Storage::disk('public')->delete($studentAcademicRecord->transcript_file);
        }
        if ($studentAcademicRecord->character_certificate_file) {
            Storage::disk('public')->delete($studentAcademicRecord->character_certificate_file);
        }

        $studentAcademicRecord->delete();

        return redirect()->route('admin.student-academic-records.index')
            ->with('success', 'Academic record deleted successfully.');
    }

    public function verify(StudentAcademicRecord $studentAcademicRecord): RedirectResponse
    {
        $studentAcademicRecord->update(['is_verified' => true]);

        return back()->with('success', 'Academic record marked as verified.');
    }
}
