<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInquiryRequest;
use App\Http\Requests\Admin\UpdateInquiryRequest;
use App\Models\Institution;
use App\Models\Inquiry;
use App\Models\InstitutionProgram;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InquiryController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Inquiry::with(['student', 'institution', 'institutionProgram.program', 'assignedTo']);
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
        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }
        if ($contactedFrom = $request->input('contacted_from')) {
            $query->whereDate('last_contacted_at', '>=', $contactedFrom);
        }
        if ($contactedTo = $request->input('contacted_to')) {
            $query->whereDate('last_contacted_at', '<=', $contactedTo);
        }

        $inquiries = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $sources = Inquiry::SOURCES;
        $statuses = Inquiry::STATUSES;

        return view('admin.modules.inquiries.index', compact(
            'inquiries',
            'institutions',
            'institutionPrograms',
            'students',
            'users',
            'sources',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $sources = Inquiry::SOURCES;
        $statuses = Inquiry::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedStudentId = $request->input('student_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.inquiries.create', compact(
            'institutions',
            'institutionPrograms',
            'students',
            'users',
            'sources',
            'statuses',
            'selectedInstitutionId',
            'selectedStudentId'
        ));
    }

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $inquiry = Inquiry::create($data);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Inquiry created successfully.');
    }

    public function show(Inquiry $inquiry): View
    {
        $this->authorizeInquiryAccess($inquiry);
        $inquiry->load([
            'student',
            'institution',
            'institutionProgram.program',
            'assignedTo',
            'notes' => fn ($q) => $q->with('user')->latest(),
            'followUps' => fn ($q) => $q->with('assignedTo')->orderBy('follow_up_at'),
        ]);

        return view('admin.modules.inquiries.show', compact('inquiry'));
    }

    public function edit(Inquiry $inquiry): View
    {
        $this->authorizeInquiryAccess($inquiry);
        $inquiry->load(['student', 'institution', 'institutionProgram', 'assignedTo']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get(['id', 'title', 'institution_id', 'program_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $sources = Inquiry::SOURCES;
        $statuses = Inquiry::STATUSES;

        return view('admin.modules.inquiries.edit', compact(
            'inquiry',
            'institutions',
            'institutionPrograms',
            'students',
            'users',
            'sources',
            'statuses'
        ));
    }

    public function update(UpdateInquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeInquiryAccess($inquiry);
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $inquiry->update($data);

        return redirect()->route('admin.inquiries.show', $inquiry)
            ->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeInquiryAccess($inquiry);
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $this->authorizeInquiryAccess($inquiry);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Inquiry::STATUSES))],
        ]);

        $inquiry->update(['status' => $request->input('status')]);

        return back()->with('success', 'Inquiry status updated.');
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

    private function authorizeInquiryAccess(Inquiry $inquiry): void
    {
        if ($inquiry->institution_id) {
            $this->authorizeInstitution((int) $inquiry->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this record.');
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
