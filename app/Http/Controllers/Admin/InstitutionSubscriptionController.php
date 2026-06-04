<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstitutionSubscriptionRequest;
use App\Http\Requests\Admin\UpdateInstitutionSubscriptionRequest;
use App\Models\Institution;
use App\Models\InstitutionSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionSubscriptionController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = InstitutionSubscription::with(['institution', 'subscriptionPlan']);
        $this->applyInstitutionScope($query);

        if ($institutionId = $request->input('institution_id')) {
            $query->where('institution_id', $institutionId);
        }
        if ($planId = $request->input('subscription_plan_id')) {
            $query->where('subscription_plan_id', $planId);
        }
        if ($billingCycle = $request->input('billing_cycle')) {
            $query->where('billing_cycle', $billingCycle);
        }
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($startsFrom = $request->input('starts_from')) {
            $query->whereDate('starts_at', '>=', $startsFrom);
        }
        if ($endsTo = $request->input('ends_to')) {
            $query->whereDate('ends_at', '<=', $endsTo);
        }

        $subscriptions = $query->latest('starts_at')->paginate(20)->withQueryString();
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $plans = SubscriptionPlan::orderBy('name')->get(['id', 'name']);
        $billingCycles = InstitutionSubscription::BILLING_CYCLES;
        $statuses = InstitutionSubscription::STATUSES;

        return view('admin.modules.institution-subscriptions.index', compact(
            'subscriptions',
            'institutions',
            'plans',
            'billingCycles',
            'statuses'
        ));
    }

    public function create(Request $request): View
    {
        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('name')->get(['id', 'name', 'price_monthly', 'price_yearly']);
        $billingCycles = InstitutionSubscription::BILLING_CYCLES;
        $statuses = InstitutionSubscription::STATUSES;
        $selectedInstitutionId = $request->input('institution_id');
        $selectedPlanId = $request->input('subscription_plan_id');

        if ($selectedInstitutionId) {
            $this->authorizeInstitution((int) $selectedInstitutionId);
        }

        return view('admin.modules.institution-subscriptions.create', compact(
            'institutions',
            'plans',
            'billingCycles',
            'statuses',
            'selectedInstitutionId',
            'selectedPlanId'
        ));
    }

    public function store(StoreInstitutionSubscriptionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $subscription = InstitutionSubscription::create($data);

        return redirect()->route('admin.institution-subscriptions.show', $subscription)
            ->with('success', 'Subscription created successfully.');
    }

    public function show(InstitutionSubscription $institutionSubscription): View
    {
        $this->authorizeSubscriptionAccess($institutionSubscription);
        $institutionSubscription->load(['institution', 'subscriptionPlan']);

        return view('admin.modules.institution-subscriptions.show', compact('institutionSubscription'));
    }

    public function edit(InstitutionSubscription $institutionSubscription): View
    {
        $this->authorizeSubscriptionAccess($institutionSubscription);
        $institutionSubscription->load(['institution', 'subscriptionPlan']);

        $institutions = $this->institutionDropdownQuery()->get(['id', 'name']);
        $plans = SubscriptionPlan::orderBy('name')->get(['id', 'name', 'price_monthly', 'price_yearly']);
        $billingCycles = InstitutionSubscription::BILLING_CYCLES;
        $statuses = InstitutionSubscription::STATUSES;

        return view('admin.modules.institution-subscriptions.edit', compact(
            'institutionSubscription',
            'institutions',
            'plans',
            'billingCycles',
            'statuses'
        ));
    }

    public function update(UpdateInstitutionSubscriptionRequest $request, InstitutionSubscription $institutionSubscription): RedirectResponse
    {
        $this->authorizeSubscriptionAccess($institutionSubscription);
        $data = $request->validated();
        $this->authorizeInstitution((int) $data['institution_id']);

        $institutionSubscription->update($data);

        return redirect()->route('admin.institution-subscriptions.show', $institutionSubscription)
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy(InstitutionSubscription $institutionSubscription): RedirectResponse
    {
        $this->authorizeSubscriptionAccess($institutionSubscription);
        $institutionSubscription->delete();

        return redirect()->route('admin.institution-subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }

    public function updateStatus(Request $request, InstitutionSubscription $institutionSubscription): RedirectResponse
    {
        $this->authorizeSubscriptionAccess($institutionSubscription);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(InstitutionSubscription::STATUSES))],
        ]);

        $institutionSubscription->update(['status' => $request->input('status')]);

        return back()->with('success', 'Subscription status updated.');
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

    private function authorizeSubscriptionAccess(InstitutionSubscription $subscription): void
    {
        $this->authorizeInstitution((int) $subscription->institution_id);
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
