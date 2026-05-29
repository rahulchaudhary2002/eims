<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreScholarshipApplicationRequest;
use App\Http\Requests\Admin\UpdateScholarshipApplicationRequest;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScholarshipApplicationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ScholarshipApplication::with(['scholarship.institution', 'student', 'application']);
        $this->applyScholarshipInstitutionScope($query);

        if ($scholarshipId = $request->input('scholarship_id')) {
            $query->where('scholarship_id', $scholarshipId);
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($applicationId = $request->input('application_id')) {
            $query->where('application_id', $applicationId);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $scholarshipApplications = $query->latest()->paginate(20)->withQueryString();
        $scholarships = $this->scholarshipDropdownQuery()->get(['id', 'title', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $statuses = ScholarshipApplication::STATUSES;

        return view('admin.modules.scholarship-applications.index', compact(
            'scholarshipApplications',
            'scholarships',
            'students',
            'applications',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $scholarships = $this->scholarshipDropdownQuery()->get(['id', 'title', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $statuses = ScholarshipApplication::STATUSES;
        $selectedScholarshipId = $request->input('scholarship_id');
        $selectedStudentId = $request->input('student_id');
        $selectedApplicationId = $request->input('application_id');

        if ($selectedScholarshipId) {
            $scholarship = Scholarship::findOrFail($selectedScholarshipId);
            $this->authorizeInstitution((int) $scholarship->institution_id);
        }

        return view('admin.modules.scholarship-applications.create', compact(
            'scholarships',
            'students',
            'applications',
            'statuses',
            'selectedScholarshipId',
            'selectedStudentId',
            'selectedApplicationId'
        ));
    }

    public function store(StoreScholarshipApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $scholarship = Scholarship::findOrFail($data['scholarship_id']);
        $this->authorizeInstitution((int) $scholarship->institution_id);

        $sa = ScholarshipApplication::create($data);

        return redirect()->route('admin.scholarship-applications.show', $sa)
            ->with('success', 'Scholarship application created successfully.');
    }

    public function show(ScholarshipApplication $scholarshipApplication): View
    {
        $this->authorizeScholarshipApplicationAccess($scholarshipApplication);
        $scholarshipApplication->load(['scholarship.institution', 'student', 'application.institutionProgram.program']);

        return view('admin.modules.scholarship-applications.show', compact('scholarshipApplication'));
    }

    public function edit(ScholarshipApplication $scholarshipApplication): View
    {
        $this->authorizeScholarshipApplicationAccess($scholarshipApplication);
        $scholarshipApplication->load(['scholarship', 'student', 'application']);

        $scholarships = $this->scholarshipDropdownQuery()->get(['id', 'title', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'institution_id']);
        $statuses = ScholarshipApplication::STATUSES;

        return view('admin.modules.scholarship-applications.edit', compact(
            'scholarshipApplication',
            'scholarships',
            'students',
            'applications',
            'statuses'
        ));
    }

    public function update(UpdateScholarshipApplicationRequest $request, ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $this->authorizeScholarshipApplicationAccess($scholarshipApplication);
        $data = $request->validated();

        $scholarship = Scholarship::findOrFail($data['scholarship_id']);
        $this->authorizeInstitution((int) $scholarship->institution_id);

        $scholarshipApplication->update($data);

        return redirect()->route('admin.scholarship-applications.show', $scholarshipApplication)
            ->with('success', 'Scholarship application updated successfully.');
    }

    public function destroy(ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $this->authorizeScholarshipApplicationAccess($scholarshipApplication);
        $scholarshipApplication->delete();

        return redirect()->route('admin.scholarship-applications.index')
            ->with('success', 'Scholarship application deleted successfully.');
    }

    public function updateStatus(Request $request, ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $this->authorizeScholarshipApplicationAccess($scholarshipApplication);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(ScholarshipApplication::STATUSES))],
        ]);

        $scholarshipApplication->update(['status' => $request->input('status')]);

        return back()->with('success', 'Status updated.');
    }

    private function scholarshipDropdownQuery(): Builder
    {
        $query = Scholarship::orderBy('title');
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

    private function applicationDropdownQuery(): Builder
    {
        $query = Application::orderBy('application_number');
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

    private function applyScholarshipInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('scholarship', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function authorizeScholarshipApplicationAccess(ScholarshipApplication $sa): void
    {
        $sa->loadMissing('scholarship');
        $this->authorizeInstitution((int) $sa->scholarship?->institution_id);
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
