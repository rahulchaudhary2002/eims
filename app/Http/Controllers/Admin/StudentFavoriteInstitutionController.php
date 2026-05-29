<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentFavoriteInstitutionRequest;
use App\Http\Requests\Admin\UpdateStudentFavoriteInstitutionRequest;
use App\Models\Institution;
use App\Models\Student;
use App\Models\StudentFavoriteInstitution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentFavoriteInstitutionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = StudentFavoriteInstitution::with(['student', 'institution']);
        $this->applyInstitutionScope($query);

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $favorites = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.student-favorite-institutions.index', compact('favorites', 'students', 'institutions'));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.student-favorite-institutions.create', compact(
            'students',
            'institutions',
            'selectedStudentId',
            'selectedInstitutionId'
        ));
    }

    public function store(StoreStudentFavoriteInstitutionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $favorite = StudentFavoriteInstitution::create($data);

        return redirect()->route('admin.student-favorite-institutions.show', $favorite)
            ->with('success', 'Favorite institution added successfully.');
    }

    public function show(StudentFavoriteInstitution $studentFavoriteInstitution): View
    {
        $this->authorizeFavoriteAccess($studentFavoriteInstitution);
        $studentFavoriteInstitution->load(['student', 'institution']);

        return view('admin.student-favorite-institutions.show', compact('studentFavoriteInstitution'));
    }

    public function edit(StudentFavoriteInstitution $studentFavoriteInstitution): View
    {
        $this->authorizeFavoriteAccess($studentFavoriteInstitution);
        $studentFavoriteInstitution->load(['student', 'institution']);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.student-favorite-institutions.edit', compact(
            'studentFavoriteInstitution',
            'students',
            'institutions'
        ));
    }

    public function update(UpdateStudentFavoriteInstitutionRequest $request, StudentFavoriteInstitution $studentFavoriteInstitution): RedirectResponse
    {
        $this->authorizeFavoriteAccess($studentFavoriteInstitution);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $studentFavoriteInstitution->update($data);

        return redirect()->route('admin.student-favorite-institutions.show', $studentFavoriteInstitution)
            ->with('success', 'Favorite institution updated successfully.');
    }

    public function destroy(StudentFavoriteInstitution $studentFavoriteInstitution): RedirectResponse
    {
        $this->authorizeFavoriteAccess($studentFavoriteInstitution);
        $studentFavoriteInstitution->delete();

        return redirect()->route('admin.student-favorite-institutions.index')
            ->with('success', 'Favorite institution removed successfully.');
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

    private function authorizeFavoriteAccess(StudentFavoriteInstitution $favorite): void
    {
        $this->authorizeInstitution((int) $favorite->institution_id);
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
