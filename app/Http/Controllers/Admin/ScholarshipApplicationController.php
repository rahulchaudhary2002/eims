<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;
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
        $query = Application::with(['scholarship.institution', 'student', 'institution', 'applicable'])
            ->whereNotNull('scholarship_id');

        $this->applyInstitutionScope($query);

        if ($scholarshipId = $request->input('scholarship_id')) {
            $query->where('scholarship_id', $scholarshipId);
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($status = $request->input('scholarship_status')) {
            $query->where('scholarship_status', $status);
        }

        $scholarshipApplications = $query->latest()->paginate(20)->withQueryString();
        $scholarships = $this->scholarshipDropdownQuery()->get(['id', 'title', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = Application::SCHOLARSHIP_STATUSES;

        return view('admin.modules.scholarship-applications.index', compact(
            'scholarshipApplications',
            'scholarships',
            'students',
            'statuses'
        ));
    }

    public function show(Application $scholarshipApplication): View
    {
        abort_unless($scholarshipApplication->scholarship_id !== null, 404);
        $this->authorizeApplicationAccess($scholarshipApplication);
        $scholarshipApplication->load(['scholarship.institution', 'student', 'institution', 'applicable']);

        return view('admin.modules.scholarship-applications.show', compact('scholarshipApplication'));
    }

    public function updateStatus(Request $request, Application $scholarshipApplication): RedirectResponse
    {
        abort_unless($scholarshipApplication->scholarship_id !== null, 404);
        $this->authorizeApplicationAccess($scholarshipApplication);

        $request->validate([
            'scholarship_status'          => ['required', 'in:' . implode(',', array_keys(Application::SCHOLARSHIP_STATUSES))],
            'scholarship_approved_amount' => ['nullable', 'numeric', 'min:0'],
            'scholarship_remarks'         => ['nullable', 'string', 'max:2000'],
        ]);

        $scholarshipApplication->update([
            'scholarship_status'          => $request->input('scholarship_status'),
            'scholarship_approved_amount' => $request->input('scholarship_approved_amount'),
            'scholarship_remarks'         => $request->input('scholarship_remarks'),
        ]);

        return back()->with('success', 'Scholarship application status updated.');
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

    private function authorizeApplicationAccess(Application $application): void
    {
        $this->authorizeInstitution((int) $application->institution_id);
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
