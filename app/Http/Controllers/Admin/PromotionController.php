<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePromotionRequest;
use App\Http\Requests\Admin\UpdatePromotionRequest;
use App\Models\Institution;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PromotionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Promotion::with('institution');
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($startFrom = $request->input('start_from')) {
            $query->whereDate('start_date', '>=', $startFrom);
        }
        if ($endTo = $request->input('end_to')) {
            $query->whereDate('end_date', '<=', $endTo);
        }
        if ($amountMin = $request->input('amount_min')) {
            $query->where('amount', '>=', $amountMin);
        }
        if ($amountMax = $request->input('amount_max')) {
            $query->where('amount', '<=', $amountMax);
        }

        $promotions = $query->latest()->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $types = Promotion::TYPES;
        $statuses = Promotion::STATUSES;

        return view('admin.promotions.index', compact('promotions', 'institutions', 'types', 'statuses'));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $types = Promotion::TYPES;
        $statuses = Promotion::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.promotions.create', compact('institutions', 'types', 'statuses', 'selectedInstitutionId'));
    }

    public function store(StorePromotionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion = Promotion::create($data);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion): View
    {
        $this->authorizePromotionAccess($promotion);
        $promotion->load('institution');

        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion): View
    {
        $this->authorizePromotionAccess($promotion);
        $promotion->load('institution');

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $types = Promotion::TYPES;
        $statuses = Promotion::STATUSES;

        return view('admin.promotions.edit', compact('promotion', 'institutions', 'types', 'statuses'));
    }

    public function update(UpdatePromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $this->authorizePromotionAccess($promotion);
        $data = $request->validated();

        if (! empty($data['institution_id'])) {
            $this->authorizeInstitution((int) $data['institution_id']);
        }

        if ($request->hasFile('image')) {
            if ($promotion->image) {
                Storage::disk('public')->delete($promotion->image);
            }
            $data['image'] = $request->file('image')->store('promotions', 'public');
        } else {
            unset($data['image']);
        }

        $promotion->update($data);

        return redirect()->route('admin.promotions.show', $promotion)
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $this->authorizePromotionAccess($promotion);

        if ($promotion->image) {
            Storage::disk('public')->delete($promotion->image);
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion deleted successfully.');
    }

    public function updateStatus(Request $request, Promotion $promotion): RedirectResponse
    {
        $this->authorizePromotionAccess($promotion);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Promotion::STATUSES))],
        ]);

        $promotion->update(['status' => $request->input('status')]);

        return back()->with('success', 'Promotion status updated.');
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

    private function authorizePromotionAccess(Promotion $promotion): void
    {
        if ($promotion->institution_id) {
            $this->authorizeInstitution((int) $promotion->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this promotion.');
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
