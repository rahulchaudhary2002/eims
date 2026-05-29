<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReferralRequest;
use App\Http\Requests\Admin\UpdateReferralRequest;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Referral;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferralController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Referral::with(['application', 'student', 'institution', 'referredBy']);
        $this->applyInstitutionScope($query);

        if ($applicationId = $request->input('application_id')) {
            $query->where('application_id', $applicationId);
        }
        if ($studentId = $request->input('student_id')) {
            $query->where('student_id', $studentId);
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($referredBy = $request->input('referred_by')) {
            $query->where('referred_by', $referredBy);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('referred_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('referred_at', '<=', $dateTo);
        }

        $referrals = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'student_id', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = Referral::STATUSES;

        return view('admin.modules.referrals.index', compact(
            'referrals',
            'institutions',
            'applications',
            'students',
            'users',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'student_id', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = Referral::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedApplicationId = $request->input('application_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.referrals.create', compact(
            'institutions',
            'applications',
            'students',
            'users',
            'statuses',
            'selectedInstitutionId',
            'selectedApplicationId'
        ));
    }

    public function store(StoreReferralRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['referral_number'] = ($data['referral_number'] ?? '') ?: $this->nextReferralNumber();
        $data['referred_at'] = $data['referred_at'] ?? now();

        $referral = Referral::create($data);

        return redirect()->route('admin.referrals.show', $referral)
            ->with('success', 'Referral created successfully.');
    }

    public function show(Referral $referral): View
    {
        $this->authorizeReferralAccess($referral);
        $referral->load(['application.institutionProgram.program', 'student', 'institution', 'referredBy']);

        return view('admin.modules.referrals.show', compact('referral'));
    }

    public function edit(Referral $referral): View
    {
        $this->authorizeReferralAccess($referral);
        $referral->load(['application', 'student', 'institution', 'referredBy']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $applications = $this->applicationDropdownQuery()->get(['id', 'application_number', 'student_id', 'institution_id']);
        $students = Student::orderBy('name')->get(['id', 'name', 'email']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = Referral::STATUSES;

        return view('admin.modules.referrals.edit', compact(
            'referral',
            'institutions',
            'applications',
            'students',
            'users',
            'statuses'
        ));
    }

    public function update(UpdateReferralRequest $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $data['referral_number'] = ($data['referral_number'] ?? '') ?: $referral->referral_number;

        $referral->update($data);

        return redirect()->route('admin.referrals.show', $referral)
            ->with('success', 'Referral updated successfully.');
    }

    public function destroy(Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);
        $referral->delete();

        return redirect()->route('admin.referrals.index')
            ->with('success', 'Referral deleted successfully.');
    }

    public function updateStatus(Request $request, Referral $referral): RedirectResponse
    {
        $this->authorizeReferralAccess($referral);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Referral::STATUSES))],
        ]);

        $data = ['status' => $request->input('status')];

        if ($request->input('status') === 'viewed' && ! $referral->viewed_at) {
            $data['viewed_at'] = now();
        }

        $referral->update($data);

        return back()->with('success', 'Referral status updated.');
    }

    private function nextReferralNumber(): string
    {
        do {
            $number = 'REF-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (Referral::where('referral_number', $number)->exists());

        return $number;
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

    private function authorizeReferralAccess(Referral $referral): void
    {
        $this->authorizeInstitution((int) $referral->institution_id);
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
