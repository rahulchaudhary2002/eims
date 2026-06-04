<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionDocumentRequest;
use App\Http\Requests\Admin\UpdateInstitutionDocumentRequest;
use App\Models\Institution;
use App\Models\InstitutionDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstitutionDocumentController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionDocument::with('institution');

        // Institution scope
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->where('institution_id', $scope);
        }

        // Filters
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($documentType = $request->input('document_type')) {
            $query->where('document_type', $documentType);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $documents = $query->latest()->paginate(20)->withQueryString();

        // Dropdown data (scoped)
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions   = $institutionsQuery->get(['id', 'name']);
        $documentTypes  = InstitutionDocument::DOCUMENT_TYPES;
        $statuses       = InstitutionDocument::STATUSES;

        return view('admin.modules.institution-documents.index', compact(
            'documents', 'institutions', 'documentTypes', 'statuses'
        ));
    }

    public function create(): View
    {
        $scope = $this->institutionScope();
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions  = $institutionsQuery->get(['id', 'name']);
        $documentTypes = InstitutionDocument::DOCUMENT_TYPES;
        $statuses      = InstitutionDocument::STATUSES;

        return view('admin.modules.institution-documents.create', compact(
            'institutions', 'documentTypes', 'statuses'
        ));
    }

    public function store(StoreInstitutionDocumentRequest $request): RedirectResponse
    {
        $this->authorizeDocumentInstitution((int) $request->validated('institution_id'));

        $data = $request->validated();

        // Handle file upload
        $data['file_path'] = $request->file('file_path')
            ->store('institution-documents', 'public');

        $document = InstitutionDocument::create($data);

        return redirect()->route('admin.institution-documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(InstitutionDocument $institutionDocument): View
    {
        $this->authorizeDocumentAccess($institutionDocument);
        $institutionDocument->load('institution');

        return view('admin.modules.institution-documents.show', [
            'document'     => $institutionDocument,
            'documentTypes'=> InstitutionDocument::DOCUMENT_TYPES,
            'statuses'     => InstitutionDocument::STATUSES,
        ]);
    }

    public function edit(InstitutionDocument $institutionDocument): View
    {
        $this->authorizeDocumentAccess($institutionDocument);

        $scope = $this->institutionScope();
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions  = $institutionsQuery->get(['id', 'name']);
        $documentTypes = InstitutionDocument::DOCUMENT_TYPES;
        $statuses      = InstitutionDocument::STATUSES;

        return view('admin.modules.institution-documents.edit', [
            'document'     => $institutionDocument,
            'institutions' => $institutions,
            'documentTypes'=> $documentTypes,
            'statuses'     => $statuses,
        ]);
    }

    public function update(UpdateInstitutionDocumentRequest $request, InstitutionDocument $institutionDocument): RedirectResponse
    {
        $this->authorizeDocumentAccess($institutionDocument);

        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Delete old file
            Storage::disk('public')->delete($institutionDocument->file_path);
            $data['file_path'] = $request->file('file_path')
                ->store('institution-documents', 'public');
        } else {
            unset($data['file_path']);
        }

        $institutionDocument->update($data);

        return redirect()->route('admin.institution-documents.show', $institutionDocument)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(InstitutionDocument $institutionDocument): RedirectResponse
    {
        $this->authorizeDocumentAccess($institutionDocument);

        Storage::disk('public')->delete($institutionDocument->file_path);
        $institutionDocument->delete();

        return redirect()->route('admin.institution-documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function updateStatus(Request $request, InstitutionDocument $institutionDocument): RedirectResponse
    {
        $this->authorizeDocumentAccess($institutionDocument);

        $request->validate(['status' => 'required|in:active,inactive,expired']);
        $institutionDocument->update(['status' => $request->status]);

        return back()->with('success', 'Document status updated.');
    }

    // -------------------------------------------------------------------------

    private function authorizeDocumentAccess(InstitutionDocument $document): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless($document->institution_id === $scope, 403);
        }
    }

    private function authorizeDocumentInstitution(int $institutionId): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless($institutionId === $scope, 403);
        }
    }
}
