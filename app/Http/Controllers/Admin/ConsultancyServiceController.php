<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreConsultancyServiceRequest;
use App\Http\Requests\Admin\UpdateConsultancyServiceRequest;
use App\Models\ConsultancyService;
use App\Models\Institution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultancyServiceController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = ConsultancyService::with('institution')
            ->whereHas('institution', fn ($q) => $q->where('type', 'consultancy'));
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($serviceType = $request->input('service_type')) {
            $query->where('service_type', $serviceType);
        }
        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }
        if ($feeMin = $request->input('fee_min')) {
            $query->where('service_fee', '>=', $feeMin);
        }
        if ($feeMax = $request->input('fee_max')) {
            $query->where('service_fee', '<=', $feeMax);
        }

        $services = $query->orderBy('title')->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $serviceTypes = ConsultancyService::SERVICE_TYPES;

        return view('admin.modules.consultancy-services.index', compact('services', 'institutions', 'serviceTypes'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $serviceTypes = ConsultancyService::SERVICE_TYPES;
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.consultancy-services.create', compact('institutions', 'serviceTypes', 'selectedInstitutionId'));
    }

    public function store(StoreConsultancyServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->assertConsultancyType((int) $data['institution_id']);

        $service = ConsultancyService::create($data);

        return redirect()->route('admin.consultancy-services.show', $service)
            ->with('success', 'Consultancy service created successfully.');
    }

    public function show(ConsultancyService $consultancyService): View
    {
        $this->authorizeServiceAccess($consultancyService);
        $consultancyService->load('institution');

        return view('admin.modules.consultancy-services.show', compact('consultancyService'));
    }

    public function edit(ConsultancyService $consultancyService): View
    {
        $this->authorizeServiceAccess($consultancyService);
        $consultancyService->load('institution');
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $serviceTypes = ConsultancyService::SERVICE_TYPES;

        return view('admin.modules.consultancy-services.edit', compact('consultancyService', 'institutions', 'serviceTypes'));
    }

    public function update(UpdateConsultancyServiceRequest $request, ConsultancyService $consultancyService): RedirectResponse
    {
        $this->authorizeServiceAccess($consultancyService);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);
        $this->assertConsultancyType((int) $data['institution_id']);

        $consultancyService->update($data);

        return redirect()->route('admin.consultancy-services.show', $consultancyService)
            ->with('success', 'Consultancy service updated successfully.');
    }

    public function destroy(ConsultancyService $consultancyService): RedirectResponse
    {
        $this->authorizeServiceAccess($consultancyService);
        $consultancyService->delete();

        return redirect()->route('admin.consultancy-services.index')
            ->with('success', 'Consultancy service deleted successfully.');
    }

    public function updateStatus(ConsultancyService $consultancyService): RedirectResponse
    {
        $this->authorizeServiceAccess($consultancyService);
        $consultancyService->update(['is_active' => ! $consultancyService->is_active]);

        $msg = $consultancyService->is_active ? 'Service activated.' : 'Service deactivated.';

        return back()->with('success', $msg);
    }

    private function assertConsultancyType(int $institutionId): void
    {
        abort_unless(
            Institution::where('id', $institutionId)->where('type', 'consultancy')->exists(),
            422,
            'Only consultancy institutions can have services.'
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

    private function authorizeServiceAccess(ConsultancyService $service): void
    {
        $this->authorizeInstitution((int) $service->institution_id);
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
