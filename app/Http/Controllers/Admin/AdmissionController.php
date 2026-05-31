<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdmissionRequest;
use App\Http\Requests\Admin\UpdateAdmissionRequest;
use App\Models\Admission;
use App\Models\Application;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Admission::with(['application', 'student', 'institution', 'institutionProgram.program', 'verifiedBy']);
        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('admission_number', 'ilike', '%' . $search . '%')
                    ->orWhereHas('application', fn (Builder $aq) => $aq->where('application_number', 'ilike', '%' . $search . '%'))
                    ->orWhereHas('student', fn (Builder $sq) => $sq->where('name', 'ilike', '%' . $search . '%'));
            });
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($institutionProgramId = $request->input('institution_program_id')) {
            $query->where('institution_program_id', $institutionProgramId);
        }
        if ($verificationStatus = $request->input('verification_status')) {
            $query->where('verification_status', $verificationStatus);
        }
        if ($admissionFrom = $request->input('admission_from')) {
            $query->whereDate('admission_date', '>=', $admissionFrom);
        }
        if ($admissionTo = $request->input('admission_to')) {
            $query->whereDate('admission_date', '<=', $admissionTo);
        }

        $admissions = $query->latest()->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $applications = $this->applicationDropdownQuery()->get();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $verificationStatuses = Admission::VERIFICATION_STATUSES;

        return view('admin.modules.admissions.index', compact(
            'admissions',
            'students',
            'institutions',
            'institutionPrograms',
            'applications',
            'users',
            'verificationStatuses'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $applications = $this->applicationDropdownQuery()->get();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $verificationStatuses = Admission::VERIFICATION_STATUSES;
        $selectedApplicationId = $request->input('application_id');
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');
        $selectedInstitutionProgramId = $request->input('institution_program_id');

        if ($selectedApplicationId) {
            $this->authorizeApplication((int) $selectedApplicationId);
        }
        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }
        if ($selectedInstitutionProgramId) {
            $this->authorizeInstitutionProgram((int) $selectedInstitutionProgramId);
        }

        return view('admin.modules.admissions.create', compact(
            'students',
            'institutions',
            'institutionPrograms',
            'applications',
            'users',
            'verificationStatuses',
            'selectedApplicationId',
            'selectedStudentId',
            'selectedInstitutionId',
            'selectedInstitutionProgramId'
        ));
    }

    public function store(StoreAdmissionRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->validateRelationships($data);

        $admission = Admission::create($data);

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Admission created successfully.');
    }

    public function show(Admission $admission): View
    {
        $this->authorizeAdmissionAccess($admission);
        $admission->load(['application.statusLogs.changedBy', 'student', 'institution', 'institutionProgram.program.faculty', 'verifiedBy', 'commissionInvoice']);

        return view('admin.modules.admissions.show', compact('admission'));
    }

    public function edit(Admission $admission): View
    {
        $this->authorizeAdmissionAccess($admission);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $applications = $this->applicationDropdownQuery($admission)->get();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $verificationStatuses = Admission::VERIFICATION_STATUSES;
        $selectedApplicationId = null;
        $selectedStudentId = null;
        $selectedInstitutionId = null;
        $selectedInstitutionProgramId = null;

        return view('admin.modules.admissions.edit', compact(
            'admission',
            'students',
            'institutions',
            'institutionPrograms',
            'applications',
            'users',
            'verificationStatuses',
            'selectedApplicationId',
            'selectedStudentId',
            'selectedInstitutionId',
            'selectedInstitutionProgramId'
        ));
    }

    public function update(UpdateAdmissionRequest $request, Admission $admission): RedirectResponse
    {
        $this->authorizeAdmissionAccess($admission);

        $data = $this->prepareData($request, $admission);
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->validateRelationships($data);

        $admission->update($data);

        return redirect()->route('admin.admissions.show', $admission)
            ->with('success', 'Admission updated successfully.');
    }

    public function destroy(Admission $admission): RedirectResponse
    {
        $this->authorizeAdmissionAccess($admission);

        if ($admission->payment_proof) {
            Storage::disk('public')->delete($admission->payment_proof);
        }

        $admission->delete();

        return redirect()->route('admin.admissions.index')
            ->with('success', 'Admission deleted successfully.');
    }

    public function verify(Request $request, Admission $admission): RedirectResponse
    {
        $this->authorizeAdmissionAccess($admission);

        $data = $request->validate([
            'verification_status' => ['required', 'in:' . implode(',', array_keys(Admission::VERIFICATION_STATUSES))],
            'remarks' => ['nullable', 'string'],
        ]);

        $data['verified_by'] = auth('web')->id();
        $data['verified_at'] = now();

        $admission->update($data);

        return back()->with('success', 'Admission verification updated.');
    }

    private function prepareData(Request $request, ?Admission $admission = null): array
    {
        $data = $request->validated();
        $data['admission_number'] = ($data['admission_number'] ?? '') ?: $this->nextAdmissionNumber();
        $data['verified_by'] = ($data['verified_by'] ?? null) ?: null;
        $data['verified_at'] = ($data['verified_at'] ?? null) ?: null;

        if ($request->hasFile('payment_proof')) {
            if ($admission?->payment_proof) {
                Storage::disk('public')->delete($admission->payment_proof);
            }

            $data['payment_proof'] = $request->file('payment_proof')->store('admissions/payment-proofs', 'public');
        } elseif ($admission) {
            unset($data['payment_proof']);
        }

        return $data;
    }

    private function nextAdmissionNumber(): string
    {
        do {
            $number = 'ADM-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (Admission::where('admission_number', $number)->exists());

        return $number;
    }

    private function validateRelationships(array $data): void
    {
        $application = Application::findOrFail($data['application_id']);

        if (
            (int) $application->student_id !== (int) $data['student_id']
            || (int) $application->institution_id !== (int) $data['institution_id']
            || (int) $application->institution_program_id !== (int) $data['institution_program_id']
        ) {
            throw ValidationException::withMessages([
                'application_id' => 'The selected application does not match the selected student, institution, and institution program.',
            ]);
        }
    }

    private function applicationDropdownQuery(?Admission $admission = null): Builder
    {
        $query = Application::with(['student', 'institution', 'institutionProgram.program'])
            ->where(function (Builder $q) use ($admission) {
                $q->whereDoesntHave('admission');

                if ($admission) {
                    $q->orWhere('id', $admission->application_id);
                }
            })
            ->orderByDesc('created_at');

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
        $query = InstitutionProgram::with(['institution', 'program'])->orderBy('id');
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

    private function authorizeAdmissionAccess(Admission $admission): void
    {
        $this->authorizeInstitution((int) $admission->institution_id);
    }

    private function authorizeApplication(int $applicationId): void
    {
        $application = Application::findOrFail($applicationId);
        $this->authorizeInstitution((int) $application->institution_id);
    }

    private function authorizeInstitutionProgram(int $institutionProgramId): void
    {
        $program = InstitutionProgram::findOrFail($institutionProgramId);
        $this->authorizeInstitution((int) $program->institution_id);
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
