<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\InstitutionDocument;

class InstitutionDocumentController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = InstitutionDocument::class;
        $this->routeBase = 'documents';
        $this->title = 'Document';
        $this->fileFields = ['file_path' => 'documents'];
        $this->selectOptions = [
            'document_type' => InstitutionDocument::DOCUMENT_TYPES,
            'status' => InstitutionDocument::STATUSES,
        ];
        $this->fields = [
            'document_type' => ['label' => 'Document Type', 'type' => 'select', 'rules' => ['required', 'string']],
            'title' => ['label' => 'Title', 'rules' => ['required', 'string', 'max:255']],
            'file_path' => ['label' => 'File', 'type' => 'file', 'rules' => ['nullable', 'file', 'max:10240']],
            'status' => ['label' => 'Status', 'type' => 'select', 'rules' => ['required', 'string']],
            'remarks' => ['label' => 'Remarks', 'type' => 'textarea', 'rules' => ['nullable', 'string']],
        ];
    }
}
