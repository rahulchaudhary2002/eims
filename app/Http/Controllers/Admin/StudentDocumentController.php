<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentDocumentRequest;
use App\Http\Requests\Admin\UpdateStudentDocumentRequest;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentDocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = StudentDocument::with('student');

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($documentType = $request->input('document_type')) {
            $query->where('document_type', $documentType);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $documents     = $query->latest()->paginate(20)->withQueryString();
        $students      = Student::orderBy('name')->get(['id', 'name', 'email']);
        $documentTypes = StudentDocument::DOCUMENT_TYPES;
        $statuses      = StudentDocument::STATUSES;

        return view('admin.modules.student-documents.index', compact(
            'documents', 'students', 'documentTypes', 'statuses'
        ));
    }

    public function create(): View
    {
        $students      = Student::orderBy('name')->get(['id', 'name', 'email']);
        $documentTypes = StudentDocument::DOCUMENT_TYPES;
        $statuses      = StudentDocument::STATUSES;

        return view('admin.modules.student-documents.create', compact(
            'students', 'documentTypes', 'statuses'
        ));
    }

    public function store(StoreStudentDocumentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['file_path'] = $request->file('file_path')
            ->store('student-documents', 'public');

        $document = StudentDocument::create($data);

        return redirect()->route('admin.student-documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(StudentDocument $studentDocument): View
    {
        $studentDocument->load('student');
        $documentTypes = StudentDocument::DOCUMENT_TYPES;
        $statuses      = StudentDocument::STATUSES;

        return view('admin.modules.student-documents.show', compact(
            'studentDocument', 'documentTypes', 'statuses'
        ));
    }

    public function edit(StudentDocument $studentDocument): View
    {
        $studentDocument->load('student');
        $students      = Student::orderBy('name')->get(['id', 'name', 'email']);
        $documentTypes = StudentDocument::DOCUMENT_TYPES;
        $statuses      = StudentDocument::STATUSES;

        return view('admin.modules.student-documents.edit', compact(
            'studentDocument', 'students', 'documentTypes', 'statuses'
        ));
    }

    public function update(UpdateStudentDocumentRequest $request, StudentDocument $studentDocument): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            Storage::disk('public')->delete($studentDocument->file_path);
            $data['file_path'] = $request->file('file_path')
                ->store('student-documents', 'public');
        } else {
            unset($data['file_path']);
        }

        $studentDocument->update($data);

        return redirect()->route('admin.student-documents.show', $studentDocument)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(StudentDocument $studentDocument): RedirectResponse
    {
        Storage::disk('public')->delete($studentDocument->file_path);
        $studentDocument->delete();

        return redirect()->route('admin.student-documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function updateStatus(Request $request, StudentDocument $studentDocument): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:active,inactive,expired',
        ]);

        $studentDocument->update(['status' => $request->input('status')]);

        return back()->with('success', 'Document status updated.');
    }
}
