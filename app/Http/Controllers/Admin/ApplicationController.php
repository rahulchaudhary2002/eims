<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreApplicationRequest;
use App\Http\Requests\Admin\UpdateApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationStatusLog;
use App\Models\Institution;
use App\Models\ConsultancyService;
use App\Models\InstitutionCertification;
use App\Models\InstitutionCourse;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Application::with(['student', 'institution', 'applicable', 'scholarship']);
        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('application_number', 'ilike', '%' . $search . '%')
                    ->orWhereHas('student', fn (Builder $sq) => $sq->where('name', 'ilike', '%' . $search . '%'));
            });
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($applicableType = $request->input('applicable_type')) {
            $query->where('applicable_type', $applicableType);
        }
        if ($applicableId = $request->input('applicable_id')) {
            $query->where('applicable_id', $applicableId);
        }
        if ($scholarshipId = $request->input('scholarship_id')) {
            $query->where('scholarship_id', $scholarshipId);
        }
        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($submittedFrom = $request->input('submitted_from')) {
            $query->whereDate('submitted_at', '>=', $submittedFrom);
        }
        if ($submittedTo = $request->input('submitted_to')) {
            $query->whereDate('submitted_at', '<=', $submittedTo);
        }

        $applications = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applicables = $this->applicablesForDropdown();
        $scholarships = $this->scholarshipDropdownQuery()->get();
        $sources = Application::SOURCES;
        $statuses = Application::STATUSES;

        return view('admin.modules.applications.index', compact(
            'applications',
            'students',
            'institutions',
            'applicables',
            'scholarships',
            'sources',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applicables = $this->applicablesForDropdown();
        $scholarships = $this->scholarshipDropdownQuery()->get();
        $sources = Application::SOURCES;
        $statuses = Application::STATUSES;
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');
        $selectedApplicableType = $request->input('applicable_type');
        $selectedApplicableId = $request->input('applicable_id');
        $selectedScholarshipId = $request->input('scholarship_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }
        if ($selectedScholarshipId) {
            $this->authorizeScholarship((int) $selectedScholarshipId);
        }

        return view('admin.modules.applications.create', compact(
            'students',
            'institutions',
            'applicables',
            'scholarships',
            'sources',
            'statuses',
            'selectedStudentId',
            'selectedInstitutionId',
            'selectedApplicableType',
            'selectedApplicableId',
            'selectedScholarshipId'
        ));
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request->validated());

        $this->authorizeInstitution((int) $data['institution_id']);
        $this->validateRelationships($data);

        $application = Application::create($data);

        $this->recordStatusLog($application, null, $application->status, $data['admin_remarks'] ?? null);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application created successfully.');
    }

    public function show(Application $application): View
    {
        $this->authorizeApplicationAccess($application);
        $application->load(['student', 'institution', 'applicable', 'scholarship', 'statusLogs.changedBy', 'admission.verifiedBy', 'referral.referredBy']);

        return view('admin.modules.applications.show', compact('application'));
    }

    public function edit(Application $application): View
    {
        $this->authorizeApplicationAccess($application);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applicables = $this->applicablesForDropdown();
        $scholarships = $this->scholarshipDropdownQuery()->get();
        $sources = Application::SOURCES;
        $statuses = Application::STATUSES;
        $selectedStudentId = null;
        $selectedInstitutionId = null;
        $selectedApplicableType = null;
        $selectedApplicableId = null;
        $selectedScholarshipId = null;

        return view('admin.modules.applications.edit', compact(
            'application',
            'students',
            'institutions',
            'applicables',
            'scholarships',
            'sources',
            'statuses',
            'selectedStudentId',
            'selectedInstitutionId',
            'selectedApplicableType',
            'selectedApplicableId',
            'selectedScholarshipId'
        ));
    }

    public function update(UpdateApplicationRequest $request, Application $application): RedirectResponse
    {
        $this->authorizeApplicationAccess($application);

        $data = $this->prepareData($request->validated(), $application);
        $fromStatus = $application->status;

        $this->authorizeInstitution((int) $data['institution_id']);
        $this->validateRelationships($data);

        $application->update($data);
        $this->recordStatusLog($application, $fromStatus, $application->status, $data['admin_remarks'] ?? null);

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Application updated successfully.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $this->authorizeApplicationAccess($application);
        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application deleted successfully.');
    }

    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplicationAccess($application);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Application::STATUSES))],
            'remarks' => ['nullable', 'string'],
        ]);

        $fromStatus = $application->status;
        $data = $this->statusTimestamps(['status' => $request->input('status')], $application);

        $application->update($data);
        $this->recordStatusLog($application, $fromStatus, $application->status, $request->input('remarks'));

        return back()->with('success', 'Application status updated.');
    }

    private function prepareData(array $data, ?Application $application = null): array
    {
        $data['application_number'] = ($data['application_number'] ?? '') ?: $this->nextApplicationNumber();
        $data['scholarship_id'] = ($data['scholarship_id'] ?? null) ?: null;

        return $this->statusTimestamps($data, $application);
    }

    private function statusTimestamps(array $data, ?Application $application = null): array
    {
        $status = $data['status'] ?? $application?->status;

        if ($status === 'submitted' && empty($data['submitted_at']) && ! $application?->submitted_at) {
            $data['submitted_at'] = now();
        }
        if ($status === 'under_review' && empty($data['reviewed_at']) && ! $application?->reviewed_at) {
            $data['reviewed_at'] = now();
        }
        if ($status === 'referred' && empty($data['referred_at']) && ! $application?->referred_at) {
            $data['referred_at'] = now();
        }
        if ($status === 'admitted' && empty($data['admitted_at']) && ! $application?->admitted_at) {
            $data['admitted_at'] = now();
        }

        return $data;
    }

    private function nextApplicationNumber(): string
    {
        do {
            $number = 'APP-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (Application::where('application_number', $number)->exists());

        return $number;
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

    private function applicablesForDropdown(): array
    {
        $scope = $this->institutionScope();
        $notAssigned = $scope !== null && !$this->currentInstitutionIsAssigned();

        $fetch = function ($query) use ($scope, $notAssigned) {
            if ($notAssigned) {
                return $query->whereRaw('1 = 0')->get();
            }
            if ($scope !== null) {
                $query->where('institution_id', $scope);
            }
            return $query->get();
        };

        return [
            \App\Models\InstitutionProgram::class      => $fetch(\App\Models\InstitutionProgram::with('program')->orderBy('id')),
            \App\Models\InstitutionCourse::class        => $fetch(\App\Models\InstitutionCourse::orderBy('title')),
            \App\Models\InstitutionCertification::class => $fetch(\App\Models\InstitutionCertification::orderBy('title')),
            \App\Models\ConsultancyService::class       => $fetch(\App\Models\ConsultancyService::orderBy('title')),
        ];
    }

    private function scholarshipDropdownQuery(): Builder
    {
        $query = Scholarship::with(['institution', 'institutionProgram.program'])->orderBy('title');
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

    private function validateRelationships(array $data): void
    {
        $type = $data['applicable_type'] ?? null;
        $id   = $data['applicable_id'] ?? null;
        $allowedTypes = array_keys(Application::APPLICABLE_TYPES);

        if (!$type || !in_array($type, $allowedTypes, true)) {
            throw ValidationException::withMessages([
                'applicable_type' => 'Please select a valid applicable type.',
            ]);
        }

        $exists = $type::whereKey($id)
            ->where('institution_id', $data['institution_id'])
            ->exists();

        if (!$exists) {
            throw ValidationException::withMessages([
                'applicable_id' => 'The selected item does not belong to the selected institution.',
            ]);
        }

        if (!empty($data['scholarship_id']) && !Scholarship::whereKey($data['scholarship_id'])
            ->where('institution_id', $data['institution_id'])
            ->exists()) {
            throw ValidationException::withMessages([
                'scholarship_id' => 'The selected scholarship does not belong to the selected institution.',
            ]);
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

    private function authorizeScholarship(int $scholarshipId): void
    {
        $scholarship = Scholarship::findOrFail($scholarshipId);
        $this->authorizeInstitution((int) $scholarship->institution_id);
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

    private function recordStatusLog(Application $application, ?string $fromStatus, string $toStatus, ?string $remarks = null): void
    {
        if ($fromStatus === $toStatus) {
            return;
        }

        $user = auth('web')->user();

        ApplicationStatusLog::create([
            'application_id'   => $application->id,
            'from_status'      => $fromStatus,
            'to_status'        => $toStatus,
            'changed_by_type'  => $user ? $user::class : null,
            'changed_by_id'    => $user?->id,
            'remarks'          => $remarks,
        ]);
    }
}
