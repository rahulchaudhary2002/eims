<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCounselingSessionRequest;
use App\Http\Requests\Admin\UpdateCounselingSessionRequest;
use App\Models\CounselingSession;
use App\Models\Institution;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselingSessionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = CounselingSession::with(['student', 'institution', 'counselor']);
        $this->applyInstitutionScope($query);

        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($counselorId = $request->input('counselor_id')) {
            $query->where('counselor_id', $counselorId);
        }
        if ($mode = $request->input('mode')) {
            $query->where('mode', $mode);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('scheduled_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('scheduled_at', '<=', $dateTo);
        }

        $sessions = $query->orderBy('scheduled_at')->paginate(20)->withQueryString();
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $modes = CounselingSession::MODES;
        $statuses = CounselingSession::STATUSES;

        return view('admin.counseling-sessions.index', compact(
            'sessions',
            'students',
            'institutions',
            'users',
            'modes',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $modes = CounselingSession::MODES;
        $statuses = CounselingSession::STATUSES;
        $selectedStudentId = $request->input('student_id');
        $selectedInstitutionId = $request->input('institution_id');
        $defaultCounselorId = auth('web')->id();

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.counseling-sessions.create', compact(
            'students',
            'institutions',
            'users',
            'modes',
            'statuses',
            'selectedStudentId',
            'selectedInstitutionId',
            'defaultCounselorId'
        ));
    }

    public function store(StoreCounselingSessionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $session = CounselingSession::create($data);

        return redirect()->route('admin.counseling-sessions.show', $session)
            ->with('success', 'Counseling session scheduled successfully.');
    }

    public function show(CounselingSession $counselingSession): View
    {
        $this->authorizeSessionAccess($counselingSession);
        $counselingSession->load(['student', 'institution', 'counselor']);

        return view('admin.counseling-sessions.show', compact('counselingSession'));
    }

    public function edit(CounselingSession $counselingSession): View
    {
        $this->authorizeSessionAccess($counselingSession);
        $counselingSession->load(['student', 'institution', 'counselor']);

        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $modes = CounselingSession::MODES;
        $statuses = CounselingSession::STATUSES;

        return view('admin.counseling-sessions.edit', compact(
            'counselingSession',
            'students',
            'institutions',
            'users',
            'modes',
            'statuses'
        ));
    }

    public function update(UpdateCounselingSessionRequest $request, CounselingSession $counselingSession): RedirectResponse
    {
        $this->authorizeSessionAccess($counselingSession);
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        $counselingSession->update($data);

        return redirect()->route('admin.counseling-sessions.show', $counselingSession)
            ->with('success', 'Counseling session updated successfully.');
    }

    public function destroy(CounselingSession $counselingSession): RedirectResponse
    {
        $this->authorizeSessionAccess($counselingSession);
        $counselingSession->delete();

        return redirect()->route('admin.counseling-sessions.index')
            ->with('success', 'Counseling session deleted successfully.');
    }

    public function updateStatus(Request $request, CounselingSession $counselingSession): RedirectResponse
    {
        $this->authorizeSessionAccess($counselingSession);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(CounselingSession::STATUSES))],
        ]);

        $counselingSession->update(['status' => $request->input('status')]);

        return back()->with('success', 'Session status updated.');
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

    private function authorizeSessionAccess(CounselingSession $session): void
    {
        if ($session->institution_id) {
            $this->authorizeInstitution((int) $session->institution_id);
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
