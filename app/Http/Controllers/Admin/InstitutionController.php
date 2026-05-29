<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionRequest;
use App\Http\Requests\Admin\UpdateInstitutionRequest;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstitutionController extends Controller
{
    use ScopesForInstitution;
    public function index(Request $request): View
    {
        $query = Institution::query();

        // Institution scope: non-super-admins only see their assigned institutions
        $scope = $this->institutionScope();
        if ($scope !== null) {
            $query->whereHas('users', fn($q) => $q->where('users.id', auth('web')->id())->wherePivot('is_active', true));
        }

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($request->filled('is_verified')) {
            $query->where('is_verified', (bool) $request->input('is_verified'));
        }
        if ($request->filled('is_featured')) {
            $query->where('is_featured', (bool) $request->input('is_featured'));
        }
        if ($province = $request->input('province')) {
            $query->where('province', $province);
        }
        if ($district = $request->input('district')) {
            $query->where('district', $district);
        }
        if ($city = $request->input('city')) {
            $query->where('city', $city);
        }

        $institutions = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        $types    = Institution::TYPES;
        $statuses = Institution::STATUSES;

        // Distinct values for location filters
        $provinces = Institution::whereNotNull('province')->distinct()->orderBy('province')->pluck('province');
        $districts = Institution::whereNotNull('district')->distinct()->orderBy('district')->pluck('district');
        $cities    = Institution::whereNotNull('city')->distinct()->orderBy('city')->pluck('city');

        return view('admin.modules.institution.index', compact(
            'institutions', 'types', 'statuses', 'provinces', 'districts', 'cities'
        ));
    }

    public function create(): View
    {
        $types    = Institution::TYPES;
        $statuses = Institution::STATUSES;
        $parents  = Institution::orderBy('name')->pluck('name', 'id');

        return view('admin.modules.institution.create', compact('types', 'statuses', 'parents'));
    }

    public function store(StoreInstitutionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['is_verified'] = $request->boolean('is_verified');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? 0;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('institutions/logos', 'public');
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('institutions/covers', 'public');
        }

        $institution = Institution::create($data);

        return redirect()->route('admin.institutions.show', $institution)
            ->with('success', 'Institution created successfully.');
    }

    public function show(Institution $institution): View
    {
        $this->authorizeInstitutionAccess($institution);
        $institution->load([
            'users',
            'profile',
            'documents',
            'scholarships' => fn ($q) => $q->with('institutionProgram.program')->latest(),
            'applications' => fn ($q) => $q->with(['student', 'institutionProgram.program', 'scholarship'])->latest(),
            'programs' => fn ($q) => $q->with('program.faculty')->orderBy('created_at', 'desc'),
            'referralAgreements' => fn ($q) => $q->latest(),
            'referrals' => fn ($q) => $q->with(['student', 'referredBy'])->latest(),
            'commissionInvoices' => fn ($q) => $q->with('admission')->latest(),
            'inquiries' => fn ($q) => $q->with('assignedTo')->latest(),
        ]);

        return view('admin.modules.institution.show', compact('institution'));
    }

    public function edit(Institution $institution): View
    {
        $this->authorizeInstitutionAccess($institution);
        $types    = Institution::TYPES;
        $statuses = Institution::STATUSES;
        $parents  = Institution::where('id', '!=', $institution->id)->orderBy('name')->pluck('name', 'id');

        return view('admin.modules.institution.edit', compact('institution', 'types', 'statuses', 'parents'));
    }

    public function update(UpdateInstitutionRequest $request, Institution $institution): RedirectResponse
    {
        $this->authorizeInstitutionAccess($institution);
        $data = $request->validated();

        $data['is_verified'] = $request->boolean('is_verified');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? 0;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($institution->logo) {
                Storage::disk('public')->delete($institution->logo);
            }
            $data['logo'] = $request->file('logo')->store('institutions/logos', 'public');
        } else {
            unset($data['logo']);
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            if ($institution->cover_image) {
                Storage::disk('public')->delete($institution->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('institutions/covers', 'public');
        } else {
            unset($data['cover_image']);
        }

        $institution->update($data);

        return redirect()->route('admin.institutions.show', $institution)
            ->with('success', 'Institution updated successfully.');
    }

    public function destroy(Institution $institution): RedirectResponse
    {
        $this->authorizeInstitutionAccess($institution);
        if ($institution->logo) {
            Storage::disk('public')->delete($institution->logo);
        }
        if ($institution->cover_image) {
            Storage::disk('public')->delete($institution->cover_image);
        }

        $institution->delete();

        return redirect()->route('admin.institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }

    private function authorizeInstitutionAccess(Institution $institution): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            abort_unless(
                $institution->users()->where('users.id', auth('web')->id())->wherePivot('is_active', true)->exists(),
                403,
                'You do not have access to this institution.'
            );
        }
    }

    public function updateStatus(Request $request, Institution $institution): RedirectResponse
    {
        $this->authorizeInstitutionAccess($institution);
        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Institution::STATUSES))],
        ]);

        $institution->update(['status' => $request->status]);

        return back()->with('success', 'Institution status updated.');
    }
}
