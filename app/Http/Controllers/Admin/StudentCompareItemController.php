<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentCompareItemRequest;
use App\Http\Requests\Admin\UpdateStudentCompareItemRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Student;
use App\Models\StudentCompareItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCompareItemController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = StudentCompareItem::with(['student', 'institution', 'institutionProgram.program']);
        $this->applyInstitutionScope($query);

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($programId = $request->input('institution_program_id')) {
            $query->where('institution_program_id', $programId);
        }

        $compareItems = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);

        return view('admin.student-compare-items.index', compact(
            'compareItems',
            'students',
            'institutions',
            'institutionPrograms'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.student-compare-items.create', compact(
            'students',
            'institutions',
            'institutionPrograms',
            'selectedStudentId',
            'selectedInstitutionId'
        ));
    }

    public function store(StoreStudentCompareItemRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $item = StudentCompareItem::create($data);

        return redirect()->route('admin.student-compare-items.show', $item)
            ->with('success', 'Compare item added successfully.');
    }

    public function show(StudentCompareItem $studentCompareItem): View
    {
        $this->authorizeItemAccess($studentCompareItem);
        $studentCompareItem->load(['student', 'institution', 'institutionProgram.program']);

        return view('admin.student-compare-items.show', compact('studentCompareItem'));
    }

    public function edit(StudentCompareItem $studentCompareItem): View
    {
        $this->authorizeItemAccess($studentCompareItem);
        $studentCompareItem->load(['student', 'institution', 'institutionProgram']);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);

        return view('admin.student-compare-items.edit', compact(
            'studentCompareItem',
            'students',
            'institutions',
            'institutionPrograms'
        ));
    }

    public function update(UpdateStudentCompareItemRequest $request, StudentCompareItem $studentCompareItem): RedirectResponse
    {
        $this->authorizeItemAccess($studentCompareItem);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $studentCompareItem->update($data);

        return redirect()->route('admin.student-compare-items.show', $studentCompareItem)
            ->with('success', 'Compare item updated successfully.');
    }

    public function destroy(StudentCompareItem $studentCompareItem): RedirectResponse
    {
        $this->authorizeItemAccess($studentCompareItem);
        $studentCompareItem->delete();

        return redirect()->route('admin.student-compare-items.index')
            ->with('success', 'Compare item removed successfully.');
    }

    private function institutionDropdownQuery(): Builder
    {
        $query = Institution::orderBy('name');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('id', $scope)
                    ->whereHas('users', fn (Builder $q) => $q->where('users.id', auth('web')->id())->wherePivot('is_active', true));
            }
        }

        return $query;
    }

    private function institutionProgramDropdownQuery(): Builder
    {
        $query = InstitutionProgram::orderBy('id');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function applyInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }
    }

    private function authorizeItemAccess(StudentCompareItem $item): void
    {
        $this->authorizeInstitution((int) $item->institution_id);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this institution.'
        );
    }

    private function currentInstitutionIsAssigned(): bool
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
