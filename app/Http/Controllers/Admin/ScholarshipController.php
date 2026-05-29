<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreScholarshipRequest;
use App\Http\Requests\Admin\UpdateScholarshipRequest;
use App\Models\Institution;
use App\Models\InstitutionProgram;
use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Scholarship::with(['institution', 'institutionProgram.program']);
        $this->applyInstitutionScope($query);

        if ($search = $request->input('search')) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('title', 'ilike', '%' . $search . '%')
                    ->orWhere('slug', 'ilike', '%' . $search . '%');
            });
        }
        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($institutionProgramId = $request->input('institution_program_id')) {
            $query->where('institution_program_id', $institutionProgramId);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($benefitType = $request->input('benefit_type')) {
            $query->where('benefit_type', $benefitType);
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

        $scholarships = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $types = Scholarship::TYPES;
        $benefitTypes = Scholarship::BENEFIT_TYPES;
        $statuses = Scholarship::STATUSES;

        return view('admin.scholarships.index', compact(
            'scholarships',
            'institutions',
            'institutionPrograms',
            'types',
            'benefitTypes',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $types = Scholarship::TYPES;
        $benefitTypes = Scholarship::BENEFIT_TYPES;
        $statuses = Scholarship::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedInstitutionProgramId = $request->input('institution_program_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }
        if ($selectedInstitutionProgramId) {
            $this->authorizeInstitutionProgram((int) $selectedInstitutionProgramId);
        }

        return view('admin.scholarships.create', compact(
            'institutions',
            'institutionPrograms',
            'types',
            'benefitTypes',
            'statuses',
            'selectedInstitutionId',
            'selectedInstitutionProgramId'
        ));
    }

    public function store(StoreScholarshipRequest $request): RedirectResponse
    {
        $data = $this->validatedData($request->validated());

        $this->authorizeInstitution((int) $data['institution_id']);
        $this->authorizeInstitutionProgramMatchesInstitution($data);

        $scholarship = Scholarship::create($data);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship): View
    {
        $this->authorizeScholarshipAccess($scholarship);
        $scholarship->load(['institution', 'institutionProgram.program.faculty', 'applications.student']);

        return view('admin.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship): View
    {
        $this->authorizeScholarshipAccess($scholarship);

        $scholarship->load(['institution', 'institutionProgram.program']);
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $institutionPrograms = $this->institutionProgramDropdownQuery()->get();
        $types = Scholarship::TYPES;
        $benefitTypes = Scholarship::BENEFIT_TYPES;
        $statuses = Scholarship::STATUSES;
        $selectedInstitutionId = null;
        $selectedInstitutionProgramId = null;

        return view('admin.scholarships.edit', compact(
            'scholarship',
            'institutions',
            'institutionPrograms',
            'types',
            'benefitTypes',
            'statuses',
            'selectedInstitutionId',
            'selectedInstitutionProgramId'
        ));
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship): RedirectResponse
    {
        $this->authorizeScholarshipAccess($scholarship);

        $data = $this->validatedData($request->validated(), $scholarship);

        $this->authorizeInstitution((int) $data['institution_id']);
        $this->authorizeInstitutionProgramMatchesInstitution($data);

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship): RedirectResponse
    {
        $this->authorizeScholarshipAccess($scholarship);
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
    }

    public function updateStatus(Request $request, Scholarship $scholarship): RedirectResponse
    {
        $this->authorizeScholarshipAccess($scholarship);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Scholarship::STATUSES))],
        ]);

        $scholarship->update(['status' => $request->input('status')]);

        return back()->with('success', 'Scholarship status updated.');
    }

    private function validatedData(array $data, ?Scholarship $scholarship = null): array
    {
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? '') ?: $data['title'], $scholarship);
        $data['used_slots'] = $data['used_slots'] ?? 0;
        $data['total_slots'] = $data['total_slots'] ?? null;
        $data['institution_program_id'] = (int) $data['institution_program_id'];

        if ($data['total_slots'] !== null && $data['used_slots'] > $data['total_slots']) {
            throw ValidationException::withMessages([
                'used_slots' => 'Used slots cannot exceed total slots.',
            ]);
        }

        return $data;
    }

    private function uniqueSlug(string $value, ?Scholarship $scholarship = null): string
    {
        $base = Str::slug($value);
        $base = $base !== '' ? $base : Str::slug((string) Str::uuid());
        $slug = $base;
        $counter = 2;

        while (Scholarship::where('slug', $slug)
            ->when($scholarship, fn (Builder $q) => $q->whereKeyNot($scholarship->id))
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
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
        $query = InstitutionProgram::with(['institution', 'program'])
            ->orderBy('id');
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

    private function authorizeScholarshipAccess(Scholarship $scholarship): void
    {
        $this->authorizeInstitution((int) $scholarship->institution_id);
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

    private function authorizeInstitutionProgram(int $institutionProgramId): void
    {
        $program = InstitutionProgram::findOrFail($institutionProgramId);
        $this->authorizeInstitution((int) $program->institution_id);
    }

    private function authorizeInstitutionProgramMatchesInstitution(array $data): void
    {
        if (! InstitutionProgram::whereKey($data['institution_program_id'])
            ->where('institution_id', $data['institution_id'])
            ->exists()) {
            throw ValidationException::withMessages([
                'institution_program_id' => 'The selected institution program does not belong to the selected institution.',
            ]);
        }

        $this->authorizeInstitutionProgram((int) $data['institution_program_id']);
    }
}
