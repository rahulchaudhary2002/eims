<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLeadNoteRequest;
use App\Http\Requests\Admin\UpdateLeadNoteRequest;
use App\Models\Inquiry;
use App\Models\LeadNote;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadNoteController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = LeadNote::with(['inquiry', 'user']);
        $this->applyInquiryInstitutionScope($query);

        if ($inquiryId = $request->input('inquiry_id')) {
            $query->where('inquiry_id', $inquiryId);
        }
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $leadNotes = $query->latest()->paginate(20)->withQueryString();
        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.lead-notes.index', compact('leadNotes', 'inquiries', 'users'));
    }

    public function create(Request $request): View
    {
        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $selectedInquiryId = $request->input('inquiry_id');
        $defaultUserId = auth('web')->id();

        return view('admin.lead-notes.create', compact(
            'inquiries',
            'users',
            'selectedInquiryId',
            'defaultUserId'
        ));
    }

    public function store(StoreLeadNoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $inquiry = Inquiry::findOrFail($data['inquiry_id']);
        $this->authorizeInquiryAccess($inquiry);

        LeadNote::create($data);

        $redirectTo = $request->input('redirect_to', 'index');

        if ($redirectTo === 'inquiry') {
            return redirect()->route('admin.inquiries.show', $inquiry)
                ->with('success', 'Note added successfully.');
        }

        return redirect()->route('admin.lead-notes.index', ['inquiry_id' => $inquiry->id])
            ->with('success', 'Note added successfully.');
    }

    public function show(LeadNote $leadNote): View
    {
        $this->authorizeNoteAccess($leadNote);
        $leadNote->load(['inquiry', 'user']);

        return view('admin.lead-notes.show', compact('leadNote'));
    }

    public function edit(LeadNote $leadNote): View
    {
        $this->authorizeNoteAccess($leadNote);
        $leadNote->load(['inquiry', 'user']);

        $inquiries = $this->inquiryDropdownQuery()->get(['id', 'name', 'email', 'institution_id']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.lead-notes.edit', compact('leadNote', 'inquiries', 'users'));
    }

    public function update(UpdateLeadNoteRequest $request, LeadNote $leadNote): RedirectResponse
    {
        $this->authorizeNoteAccess($leadNote);
        $data = $request->validated();
        $inquiry = Inquiry::findOrFail($data['inquiry_id']);
        $this->authorizeInquiryAccess($inquiry);

        $leadNote->update($data);

        return redirect()->route('admin.lead-notes.show', $leadNote)
            ->with('success', 'Note updated successfully.');
    }

    public function destroy(LeadNote $leadNote): RedirectResponse
    {
        $this->authorizeNoteAccess($leadNote);
        $inquiryId = $leadNote->inquiry_id;
        $leadNote->delete();

        return redirect()->route('admin.lead-notes.index', ['inquiry_id' => $inquiryId])
            ->with('success', 'Note deleted successfully.');
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

    private function authorizeNoteAccess(LeadNote $note): void
    {
        $note->loadMissing('inquiry');
        $this->authorizeInquiryAccess($note->inquiry);
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
