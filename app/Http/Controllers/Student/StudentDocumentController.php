<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentDocumentRequest;
use App\Http\Requests\Student\UpdateStudentDocumentRequest;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $studentId = $request->user('student')->id;
        $documents = StudentDocument::where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.documents.index', compact('documents'));
    }

    public function create(): View
    {
        return view('student.documents.create');
    }

    public function store(StoreStudentDocumentRequest $request): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        $data      = $request->validated();
        $data['student_id'] = $studentId;
        $data['status']     = 'active';

        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')
                ->store("students/{$studentId}/documents", 'public');
        }

        StudentDocument::create($data);

        return redirect()->route('student.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Request $request, StudentDocument $document): View
    {
        abort_if($document->student_id !== $request->user('student')->id, 403);

        return view('student.documents.show', compact('document'));
    }

    public function edit(Request $request, StudentDocument $document): View
    {
        abort_if($document->student_id !== $request->user('student')->id, 403);

        return view('student.documents.edit', compact('document'));
    }

    public function update(UpdateStudentDocumentRequest $request, StudentDocument $document): RedirectResponse
    {
        abort_if($document->student_id !== $request->user('student')->id, 403);

        $studentId = $request->user('student')->id;
        $data      = $request->validated();

        if ($request->hasFile('file_path')) {
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }
            $data['file_path'] = $request->file('file_path')
                ->store("students/{$studentId}/documents", 'public');
        }

        $document->update($data);

        return redirect()->route('student.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Request $request, StudentDocument $document): RedirectResponse
    {
        abort_if($document->student_id !== $request->user('student')->id, 403);

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('student.documents.index')
            ->with('success', 'Document deleted.');
    }
}
