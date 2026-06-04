<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionProgramSubjectRequest;
use App\Http\Requests\Admin\UpdateInstitutionProgramSubjectRequest;
use App\Models\InstitutionProgram;
use App\Models\InstitutionProgramSubject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionProgramSubjectController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionProgramSubject::with([
            'institutionProgram.institution',
            'institutionProgram.program',
        ]);

        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where('subject_name', 'ilike', '%' . $search . '%');
        }
        if ($institutionProgramId = $request->input('institution_program_id')) {
            $query->where('institution_program_id', $institutionProgramId);
        }
        if ($request->filled('is_optional')) {
            $query->where('is_optional', (bool) $request->input('is_optional'));
        }

        $subjects          = $query->orderBy('subject_name')->paginate(25)->withQueryString();
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();

        return view('admin.modules.institution-program-subjects.index', compact('subjects', 'institutionPrograms'));
    }

    public function create(Request $request): View
    {
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $selectedProgramId = $request->input('institution_program_id');

        if ($selectedProgramId) {
            $this->authorizeInstitutionProgram((int) $selectedProgramId);
        }

        return view('admin.modules.institution-program-subjects.create', compact('institutionPrograms', 'selectedProgramId'));
    }

    public function store(StoreInstitutionProgramSubjectRequest $request): RedirectResponse
    {
        $data                = $request->validated();
        $data['is_optional'] = $request->boolean('is_optional');

        $this->authorizeInstitutionProgram((int) $data['institution_program_id']);

        $subject = InstitutionProgramSubject::create($data);

        return redirect()->route('admin.institution-program-subjects.show', $subject)
            ->with('success', 'Subject added successfully.');
    }

    public function show(InstitutionProgramSubject $institutionProgramSubject): View
    {
        $this->authorizeSubjectAccess($institutionProgramSubject);

        $institutionProgramSubject->load([
            'institutionProgram.institution',
            'institutionProgram.program.faculty',
        ]);

        return view('admin.modules.institution-program-subjects.show', compact('institutionProgramSubject'));
    }

    public function edit(InstitutionProgramSubject $institutionProgramSubject): View
    {
        $this->authorizeSubjectAccess($institutionProgramSubject);

        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();

        return view('admin.modules.institution-program-subjects.edit', compact('institutionProgramSubject', 'institutionPrograms'));
    }

    public function update(UpdateInstitutionProgramSubjectRequest $request, InstitutionProgramSubject $institutionProgramSubject): RedirectResponse
    {
        $this->authorizeSubjectAccess($institutionProgramSubject);

        $data                = $request->validated();
        $data['is_optional'] = $request->boolean('is_optional');

        $this->authorizeInstitutionProgram((int) $data['institution_program_id']);

        $institutionProgramSubject->update($data);

        return redirect()->route('admin.institution-program-subjects.show', $institutionProgramSubject)
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(InstitutionProgramSubject $institutionProgramSubject): RedirectResponse
    {
        $this->authorizeSubjectAccess($institutionProgramSubject);

        $institutionProgramSubject->delete();

        return redirect()->route('admin.institution-program-subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }

    private function institutionProgramDropdownQuery(): Builder
    {
        $query = InstitutionProgram::with(['institution', 'program'])
            ->whereHas('institution', fn ($q) => $q->where('type', '!=', 'consultancy'))
            ->orderBy('id');

        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->where('institution_id', $scope);
        }

        return $query;
    }

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->whereHas('institutionProgram', fn (Builder $q) => $q->where('institution_id', $scope));
        }
    }

    private function authorizeSubjectAccess(InstitutionProgramSubject $subject): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $subject->loadMissing('institutionProgram');

            abort_unless(
                $subject->institutionProgram?->institution_id === $scope,
                403,
                'You do not have access to this program subject.'
            );
        }
    }

    private function authorizeInstitutionProgram(int $institutionProgramId): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless(
                InstitutionProgram::whereKey($institutionProgramId)
                    ->where('institution_id', $scope)
                    ->exists(),
                403,
                'You do not have access to this institution program.'
            );
        }
    }
}
