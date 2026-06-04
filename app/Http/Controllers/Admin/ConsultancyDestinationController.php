<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConsultancyDestinationRequest;
use App\Http\Requests\Admin\UpdateConsultancyDestinationRequest;
use App\Models\ConsultancyDestination;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultancyDestinationController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ConsultancyDestination::with('institution')
            ->whereHas('institution', fn ($q) => $q->where('type', 'consultancy'));
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($country = $request->input('country')) {
            $query->where('country', 'ilike', '%' . $country . '%');
        }
        if ($city = $request->input('city')) {
            $query->where('city', 'ilike', '%' . $city . '%');
        }
        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $destinations = $query->orderBy('country')->orderBy('city')->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.consultancy-destinations.index', compact('destinations', 'institutions'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.consultancy-destinations.create', compact('institutions', 'selectedInstitutionId'));
    }

    public function store(StoreConsultancyDestinationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->assertConsultancyType((int) $data['institution_id']);

        $destination = ConsultancyDestination::create($data);

        return redirect()->route('admin.consultancy-destinations.show', $destination)
            ->with('success', 'Consultancy destination created successfully.');
    }

    public function show(ConsultancyDestination $consultancyDestination): View
    {
        $this->authorizeDestinationAccess($consultancyDestination);
        $consultancyDestination->load('institution');

        return view('admin.modules.consultancy-destinations.show', compact('consultancyDestination'));
    }

    public function edit(ConsultancyDestination $consultancyDestination): View
    {
        $this->authorizeDestinationAccess($consultancyDestination);
        $consultancyDestination->load('institution');
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);

        return view('admin.modules.consultancy-destinations.edit', compact('consultancyDestination', 'institutions'));
    }

    public function update(UpdateConsultancyDestinationRequest $request, ConsultancyDestination $consultancyDestination): RedirectResponse
    {
        $this->authorizeDestinationAccess($consultancyDestination);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->assertConsultancyType((int) $data['institution_id']);

        $consultancyDestination->update($data);

        return redirect()->route('admin.consultancy-destinations.show', $consultancyDestination)
            ->with('success', 'Consultancy destination updated successfully.');
    }

    public function destroy(ConsultancyDestination $consultancyDestination): RedirectResponse
    {
        $this->authorizeDestinationAccess($consultancyDestination);
        $consultancyDestination->delete();

        return redirect()->route('admin.consultancy-destinations.index')
            ->with('success', 'Consultancy destination deleted successfully.');
    }

    public function updateStatus(Request $request, ConsultancyDestination $consultancyDestination): RedirectResponse
    {
        $this->authorizeDestinationAccess($consultancyDestination);
        $consultancyDestination->update(['is_active' => ! $consultancyDestination->is_active]);

        $msg = $consultancyDestination->is_active ? 'Destination activated.' : 'Destination deactivated.';

        return back()->with('success', $msg);
    }

    private function assertConsultancyType(int $institutionId): void
    {
        abort_unless(
            Institution::where('id', $institutionId)->where('type', 'consultancy')->exists(),
            422,
            'Only consultancy institutions can have destinations.'
        );
    }

    private function institutionDropdownQuery(): Builder
    {
        $query = Institution::where('type', 'consultancy')->orderBy('name');
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

    private function authorizeDestinationAccess(ConsultancyDestination $destination): void
    {
        $this->authorizeInstitution((int) $destination->institution_id);
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
