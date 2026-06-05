<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionCertificationRequest;
use App\Http\Requests\Admin\UpdateInstitutionCertificationRequest;
use App\Models\Institution;
use App\Models\InstitutionCertification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionCertificationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionCertification::with('institution');
        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where('title', 'ilike', '%' . $search . '%');
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $certifications = $query->orderBy('title')->paginate(20)->withQueryString();
        $institutions   = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.institution-certifications.index', compact('certifications', 'institutions'));
    }

    public function create(Request $request): View
    {
        $institutions          = $this->institutionDropdownQuery()->get(['id', 'name']);
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.institution-certifications.create', compact('institutions', 'selectedInstitutionId'));
    }

    public function store(StoreInstitutionCertificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $certification = InstitutionCertification::create($data);

        return redirect()->route('admin.institution-certifications.show', $certification)
            ->with('success', 'Certification created successfully.');
    }

    public function show(InstitutionCertification $institutionCertification): View
    {
        $this->authorizeAccess($institutionCertification);
        $institutionCertification->load('institution');

        return view('admin.modules.institution-certifications.show', compact('institutionCertification'));
    }

    public function edit(InstitutionCertification $institutionCertification): View
    {
        $this->authorizeAccess($institutionCertification);
        $institutionCertification->load('institution');
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.institution-certifications.edit', compact('institutionCertification', 'institutions'));
    }

    public function update(UpdateInstitutionCertificationRequest $request, InstitutionCertification $institutionCertification): RedirectResponse
    {
        $this->authorizeAccess($institutionCertification);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $institutionCertification->update($data);

        return redirect()->route('admin.institution-certifications.show', $institutionCertification)
            ->with('success', 'Certification updated successfully.');
    }

    public function destroy(InstitutionCertification $institutionCertification): RedirectResponse
    {
        $this->authorizeAccess($institutionCertification);
        $institutionCertification->delete();

        return redirect()->route('admin.institution-certifications.index')
            ->with('success', 'Certification deleted successfully.');
    }

    public function updateStatus(InstitutionCertification $institutionCertification): RedirectResponse
    {
        $this->authorizeAccess($institutionCertification);
        $institutionCertification->update(['is_active' => ! $institutionCertification->is_active]);

        return back()->with('success', $institutionCertification->is_active ? 'Certification activated.' : 'Certification deactivated.');
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

    private function authorizeAccess(InstitutionCertification $certification): void
    {
        $this->authorizeInstitution((int) $certification->institution_id);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        /** @var \App\Models\User|null $user */
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
        /** @var \App\Models\User|null $user */
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
