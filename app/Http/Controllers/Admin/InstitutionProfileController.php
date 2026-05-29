<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionProfileRequest;
use App\Http\Requests\Admin\UpdateInstitutionProfileRequest;
use App\Models\Institution;
use App\Models\InstitutionProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionProfileController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionProfile::with('institution');

        // Institution scope
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->where('institution_id', $scope);
        }

        // Filters
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($request->filled('has_hostel')) {
            $query->where('has_hostel', (bool) $request->input('has_hostel'));
        }
        if ($request->filled('has_transportation')) {
            $query->where('has_transportation', (bool) $request->input('has_transportation'));
        }
        if ($request->filled('has_scholarship')) {
            $query->where('has_scholarship', (bool) $request->input('has_scholarship'));
        }

        $profiles = $query->latest()->paginate(15)->withQueryString();

        // Institutions for filter dropdown (scoped)
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions = $institutionsQuery->get(['id', 'name']);

        return view('admin.institution-profiles.index', compact('profiles', 'institutions'));
    }

    public function create(): View
    {
        $scope = $this->institutionScope();
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions = $institutionsQuery->get(['id', 'name']);

        return view('admin.institution-profiles.create', compact('institutions'));
    }

    public function store(StoreInstitutionProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Decode JSON tag fields
        foreach (['facilities', 'infrastructure', 'achievements', 'accreditations'] as $field) {
            $raw = $data[$field] ?? '';
            $data[$field] = ($raw !== '' && $raw !== null) ? (json_decode($raw, true) ?: []) : [];
        }

        // Boolean amenities
        $data['has_hostel']         = $request->boolean('has_hostel');
        $data['has_transportation'] = $request->boolean('has_transportation');
        $data['has_library']        = $request->boolean('has_library');
        $data['has_lab']            = $request->boolean('has_lab');
        $data['has_cafeteria']      = $request->boolean('has_cafeteria');
        $data['has_sports']         = $request->boolean('has_sports');
        $data['has_scholarship']    = $request->boolean('has_scholarship');

        $profile = InstitutionProfile::create($data);

        return redirect()->route('admin.institution-profiles.show', $profile)
            ->with('success', 'Institution profile created successfully.');
    }

    public function show(InstitutionProfile $institutionProfile): View
    {
        $this->authorizeProfileAccess($institutionProfile);
        $institutionProfile->load('institution');

        return view('admin.institution-profiles.show', compact('institutionProfile'));
    }

    public function edit(InstitutionProfile $institutionProfile): View
    {
        $this->authorizeProfileAccess($institutionProfile);

        $scope = $this->institutionScope();
        $institutionsQuery = Institution::orderBy('name');
        if ($scope !== null) {
            $institutionsQuery->where('id', $scope);
        }
        $institutions = $institutionsQuery->get(['id', 'name']);

        return view('admin.institution-profiles.edit', compact('institutionProfile', 'institutions'));
    }

    public function update(UpdateInstitutionProfileRequest $request, InstitutionProfile $institutionProfile): RedirectResponse
    {
        $this->authorizeProfileAccess($institutionProfile);

        $data = $request->validated();

        // Decode JSON tag fields
        foreach (['facilities', 'infrastructure', 'achievements', 'accreditations'] as $field) {
            $raw = $data[$field] ?? '';
            $data[$field] = ($raw !== '' && $raw !== null) ? (json_decode($raw, true) ?: []) : [];
        }

        // Boolean amenities
        $data['has_hostel']         = $request->boolean('has_hostel');
        $data['has_transportation'] = $request->boolean('has_transportation');
        $data['has_library']        = $request->boolean('has_library');
        $data['has_lab']            = $request->boolean('has_lab');
        $data['has_cafeteria']      = $request->boolean('has_cafeteria');
        $data['has_sports']         = $request->boolean('has_sports');
        $data['has_scholarship']    = $request->boolean('has_scholarship');

        $institutionProfile->update($data);

        return redirect()->route('admin.institution-profiles.show', $institutionProfile)
            ->with('success', 'Institution profile updated successfully.');
    }

    public function destroy(InstitutionProfile $institutionProfile): RedirectResponse
    {
        $this->authorizeProfileAccess($institutionProfile);
        $institutionProfile->delete();

        return redirect()->route('admin.institution-profiles.index')
            ->with('success', 'Institution profile deleted successfully.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function authorizeProfileAccess(InstitutionProfile $profile): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless(
                $profile->institution_id === $scope,
                403,
                'You do not have access to this institution profile.'
            );
        }
    }
}
