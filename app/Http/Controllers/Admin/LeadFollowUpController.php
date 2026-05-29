<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadFollowUpRequest;
use App\Http\Requests\Admin\UpdateLeadFollowUpRequest;
use App\Models\Inquiry;
use App\Models\LeadFollowUp;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadFollowUpController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = LeadFollowUp::with(['inquiry', 'assignedTo']);
        $this->applyInquiryInstitutionScope($query);

        if ($inquiryId = $request->input('inquiry_id')) {
            $query->where('inquiry_id', $inquiryId);
        }
        if ($assignedTo = $request->input('assigned_to')) {
            $query->where('assigned_to', $assignedTo);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('follow_up_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('follow_up_at', '<=', $dateTo);
        }

        $followUps = $query->orderBy('follow_up_at')->paginate(20)->withQueryString();
        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = LeadFollowUp::STATUSES;

        return view('admin.lead-follow-ups.index', compact('followUps', 'inquiries', 'users', 'statuses'));
    }

    public function create(Request $request): View
    {
        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = LeadFollowUp::STATUSES;
        $selectedInquiryId = $request->input('inquiry_id');
        $defaultAssignedTo = auth('web')->id();

        return view('admin.lead-follow-ups.create', compact(
            'inquiries',
            'users',
            'statuses',
            'selectedInquiryId',
            'defaultAssignedTo'
        ));
    }

    public function store(StoreLeadFollowUpRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $inquiry = Inquiry::findOrFail($data['inquiry_id']);
        $this->authorizeInquiryAccess($inquiry);

        $followUp = LeadFollowUp::create($data);

        return redirect()->route('admin.lead-follow-ups.show', $followUp)
            ->with('success', 'Follow-up scheduled successfully.');
    }

    public function show(LeadFollowUp $leadFollowUp): View
    {
        $this->authorizeFollowUpAccess($leadFollowUp);
        $leadFollowUp->load(['inquiry', 'assignedTo']);

        return view('admin.lead-follow-ups.show', compact('leadFollowUp'));
    }

    public function edit(LeadFollowUp $leadFollowUp): View
    {
        $this->authorizeFollowUpAccess($leadFollowUp);
        $leadFollowUp->load(['inquiry', 'assignedTo']);

        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $statuses = LeadFollowUp::STATUSES;

        return view('admin.lead-follow-ups.edit', compact('leadFollowUp', 'inquiries', 'users', 'statuses'));
    }

    public function update(UpdateLeadFollowUpRequest $request, LeadFollowUp $leadFollowUp): RedirectResponse
    {
        $this->authorizeFollowUpAccess($leadFollowUp);
        $data = $request->validated();
        $inquiry = Inquiry::findOrFail($data['inquiry_id']);
        $this->authorizeInquiryAccess($inquiry);

        $leadFollowUp->update($data);

        return redirect()->route('admin.lead-follow-ups.show', $leadFollowUp)
            ->with('success', 'Follow-up updated successfully.');
    }

    public function destroy(LeadFollowUp $leadFollowUp): RedirectResponse
    {
        $this->authorizeFollowUpAccess($leadFollowUp);
        $inquiryId = $leadFollowUp->inquiry_id;
        $leadFollowUp->delete();

        return redirect()->route('admin.lead-follow-ups.index', ['inquiry_id' => $inquiryId])
            ->with('success', 'Follow-up deleted successfully.');
    }

    public function updateStatus(Request $request, LeadFollowUp $leadFollowUp): RedirectResponse
    {
        $this->authorizeFollowUpAccess($leadFollowUp);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(LeadFollowUp::STATUSES))],
        ]);

        $leadFollowUp->update(['status' => $request->input('status')]);

        return back()->with('success', 'Follow-up status updated.');
    }

    private function inquiryDropdownQuery(): Builder
    {
        $query = Inquiry::orderBy('name');
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

    private function applyInquiryInstitutionScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('inquiry', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function authorizeFollowUpAccess(LeadFollowUp $followUp): void
    {
        $followUp->loadMissing('inquiry');
        $this->authorizeInquiryAccess($followUp->inquiry);
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
