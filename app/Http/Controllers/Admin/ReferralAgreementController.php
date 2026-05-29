<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreReferralAgreementRequest;
use App\Http\Requests\Admin\UpdateReferralAgreementRequest;
use App\Models\Institution;
use App\Models\ReferralAgreement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReferralAgreementController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ReferralAgreement::with('institution');
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($commissionType = $request->input('commission_type')) {
            $query->where('commission_type', $commissionType);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('end_date', '<=', $dateTo);
        }

        $agreements = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $commissionTypes = ReferralAgreement::COMMISSION_TYPES;
        $statuses = ReferralAgreement::STATUSES;

        return view('admin.referral-agreements.index', compact(
            'agreements',
            'institutions',
            'commissionTypes',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $commissionTypes = ReferralAgreement::COMMISSION_TYPES;
        $statuses = ReferralAgreement::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.referral-agreements.create', compact(
            'institutions',
            'commissionTypes',
            'statuses',
            'selectedInstitutionId'
        ));
    }

    public function store(StoreReferralAgreementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        if ($request->hasFile('agreement_file')) {
            $data['agreement_file'] = $request->file('agreement_file')
                ->store('referral-agreements', 'public');
        }

        $agreement = ReferralAgreement::create($data);

        return redirect()->route('admin.referral-agreements.show', $agreement)
            ->with('success', 'Referral agreement created successfully.');
    }

    public function show(ReferralAgreement $referralAgreement): View
    {
        $this->authorizeAgreementAccess($referralAgreement);
        $referralAgreement->load(['institution', 'commissionInvoices' => fn ($q) => $q->latest()]);

        return view('admin.referral-agreements.show', compact('referralAgreement'));
    }

    public function edit(ReferralAgreement $referralAgreement): View
    {
        $this->authorizeAgreementAccess($referralAgreement);
        $referralAgreement->load('institution');
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $commissionTypes = ReferralAgreement::COMMISSION_TYPES;
        $statuses = ReferralAgreement::STATUSES;

        return view('admin.referral-agreements.edit', compact(
            'referralAgreement',
            'institutions',
            'commissionTypes',
            'statuses'
        ));
    }

    public function update(UpdateReferralAgreementRequest $request, ReferralAgreement $referralAgreement): RedirectResponse
    {
        $this->authorizeAgreementAccess($referralAgreement);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        if ($request->hasFile('agreement_file')) {
            if ($referralAgreement->agreement_file) {
                Storage::disk('public')->delete($referralAgreement->agreement_file);
            }
            $data['agreement_file'] = $request->file('agreement_file')
                ->store('referral-agreements', 'public');
        } else {
            unset($data['agreement_file']);
        }

        $referralAgreement->update($data);

        return redirect()->route('admin.referral-agreements.show', $referralAgreement)
            ->with('success', 'Referral agreement updated successfully.');
    }

    public function destroy(ReferralAgreement $referralAgreement): RedirectResponse
    {
        $this->authorizeAgreementAccess($referralAgreement);

        if ($referralAgreement->agreement_file) {
            Storage::disk('public')->delete($referralAgreement->agreement_file);
        }

        $referralAgreement->delete();

        return redirect()->route('admin.referral-agreements.index')
            ->with('success', 'Referral agreement deleted successfully.');
    }

    public function updateStatus(Request $request, ReferralAgreement $referralAgreement): RedirectResponse
    {
        $this->authorizeAgreementAccess($referralAgreement);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(ReferralAgreement::STATUSES))],
        ]);

        $referralAgreement->update(['status' => $request->input('status')]);

        return back()->with('success', 'Agreement status updated.');
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

    private function authorizeAgreementAccess(ReferralAgreement $agreement): void
    {
        $this->authorizeInstitution((int) $agreement->institution_id);
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
