<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionCourseRequest;
use App\Http\Requests\Admin\UpdateInstitutionCourseRequest;
use App\Models\Institution;
use App\Models\InstitutionCourse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionCourseController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionCourse::with('institution');
        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where('title', 'ilike', '%' . $search . '%');
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $courses      = $query->orderBy('title')->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.institution-courses.index', compact('courses', 'institutions'));
    }

    public function create(Request $request): View
    {
        $institutions          = $this->institutionDropdownQuery()->get(['id', 'name']);
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.institution-courses.create', compact('institutions', 'selectedInstitutionId'));
    }

    public function store(StoreInstitutionCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $course = InstitutionCourse::create($data);

        return redirect()->route('admin.institution-courses.show', $course)
            ->with('success', 'Course created successfully.');
    }

    public function show(InstitutionCourse $institutionCourse): View
    {
        $this->authorizeAccess($institutionCourse);
        $institutionCourse->load('institution');

        return view('admin.modules.institution-courses.show', compact('institutionCourse'));
    }

    public function edit(InstitutionCourse $institutionCourse): View
    {
        $this->authorizeAccess($institutionCourse);
        $institutionCourse->load('institution');
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.institution-courses.edit', compact('institutionCourse', 'institutions'));
    }

    public function update(UpdateInstitutionCourseRequest $request, InstitutionCourse $institutionCourse): RedirectResponse
    {
        $this->authorizeAccess($institutionCourse);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $institutionCourse->update($data);

        return redirect()->route('admin.institution-courses.show', $institutionCourse)
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(InstitutionCourse $institutionCourse): RedirectResponse
    {
        $this->authorizeAccess($institutionCourse);
        $institutionCourse->delete();

        return redirect()->route('admin.institution-courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    public function updateStatus(InstitutionCourse $institutionCourse): RedirectResponse
    {
        $this->authorizeAccess($institutionCourse);
        $institutionCourse->update(['is_active' => ! $institutionCourse->is_active]);

        return back()->with('success', $institutionCourse->is_active ? 'Course activated.' : 'Course deactivated.');
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

    private function authorizeAccess(InstitutionCourse $course): void
    {
        $this->authorizeInstitution((int) $course->institution_id);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        /** @var \App\Models\User|null $user */
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
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
