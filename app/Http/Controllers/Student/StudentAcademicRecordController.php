<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentAcademicRecordRequest;
use App\Http\Requests\Student\UpdateStudentAcademicRecordRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Post;
use App\Models\StudentAcademicRecord;
use App\Models\StudentDocument;
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

        [$latestPosts, $featuredInstitutions, $openPrograms] = $this->sidebarData();

        return view('student.academic-records.index', compact('records', 'latestPosts', 'featuredInstitutions', 'openPrograms'));
    }

    public function create(): View
    {
        [$latestPosts, $featuredInstitutions, $openPrograms] = $this->sidebarData();

        return view('student.academic-records.create', compact('latestPosts', 'featuredInstitutions', 'openPrograms'));
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

        $record = StudentAcademicRecord::create($data);

        $this->saveAdditionalDocuments($request, $record, $studentId);

        return redirect()->route('student.academic-records.index')
            ->with('success', 'Academic record added successfully.');
    }

    public function show(Request $request, StudentAcademicRecord $academicRecord): View
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        $academicRecord->load('additionalDocuments');

        return view('student.academic-records.show', compact('academicRecord'));
    }

    public function edit(Request $request, StudentAcademicRecord $academicRecord): View
    {
        abort_if($academicRecord->student_id !== $request->user('student')->id, 403);

        $academicRecord->load('additionalDocuments');
        [$latestPosts, $featuredInstitutions, $openPrograms] = $this->sidebarData();

        return view('student.academic-records.edit', compact('academicRecord', 'latestPosts', 'featuredInstitutions', 'openPrograms'));
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

        $this->saveAdditionalDocuments($request, $academicRecord, $studentId);

        return redirect()->route('student.academic-records.index')
            ->with('success', 'Academic record updated successfully.');
    }

    public function destroyDocument(Request $request, StudentDocument $document): RedirectResponse
    {
        $record = $document->academicRecord;
        abort_if($record->student_id !== $request->user('student')->id, 403);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document removed.');
    }

    private function saveAdditionalDocuments(Request $request, StudentAcademicRecord $record, int $studentId): void
    {
        if (!$request->hasFile('additional_documents')) {
            return;
        }

        foreach ($request->file('additional_documents') as $index => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $title = $request->input("additional_document_titles.{$index}") ?: $file->getClientOriginalName();
            $type  = $request->input("additional_document_types.{$index}", 'other');
            $path  = $file->store("students/{$studentId}/academic/additional", 'public');

            StudentDocument::create([
                'student_id'         => $studentId,
                'academic_record_id' => $record->id,
                'document_type'      => $type,
                'title'              => $title,
                'file_path'          => $path,
                'status'             => 'active',
            ]);
        }
    }

    private function sidebarData(): array
    {
        $latestPosts = Post::where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        $featuredInstitutions = Institution::where('status', 'active')
            ->where('is_featured', true)
            ->limit(4)
            ->get();

        $openPrograms = InstitutionProgram::where('status', 'open')
            ->whereHas('institution', fn($q) => $q->where('is_featured', true))
            ->with(['program', 'institution'])
            ->latest()
            ->limit(4)
            ->get();

        return [$latestPosts, $featuredInstitutions, $openPrograms];
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
