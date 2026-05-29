<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubscriptionPlanRequest;
use App\Http\Requests\Admin\UpdateSubscriptionPlanRequest;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(Request $request): View
    {
        $query = SubscriptionPlan::query();

        if ($search = $request->input('search')) {
            $query->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('slug', 'ilike', '%' . $search . '%');
        }
        if ($request->input('is_active') !== null && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }
        if ($monthlyMin = $request->input('monthly_min')) {
            $query->where('price_monthly', '>=', $monthlyMin);
        }
        if ($monthlyMax = $request->input('monthly_max')) {
            $query->where('price_monthly', '<=', $monthlyMax);
        }
        if ($yearlyMin = $request->input('yearly_min')) {
            $query->where('price_yearly', '>=', $yearlyMin);
        }
        if ($yearlyMax = $request->input('yearly_max')) {
            $query->where('price_yearly', '<=', $yearlyMax);
        }

        $plans = $query->orderBy('price_monthly')->paginate(20)->withQueryString();

        return view('admin.subscription-plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('admin.subscription-plans.create');
    }

    public function store(StoreSubscriptionPlanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['features'] = $this->parseFeatures($data['features_text'] ?? '');
        unset($data['features_text']);

        $plan = SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.show', $plan)
            ->with('success', 'Subscription plan created successfully.');
    }

    public function show(SubscriptionPlan $subscriptionPlan): View
    {
        $this->requireSuperAdmin();
        $subscriptionPlan->load(['institutionSubscriptions' => fn ($q) => $q->with('institution')->latest('starts_at')]);

        return view('admin.subscription-plans.show', compact('subscriptionPlan'));
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        $this->requireSuperAdmin();

        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $this->requireSuperAdmin();

        $data = $request->validated();
        $data['features'] = $this->parseFeatures($data['features_text'] ?? '');
        unset($data['features_text']);

        $subscriptionPlan->update($data);

        return redirect()->route('admin.subscription-plans.show', $subscriptionPlan)
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $this->requireSuperAdmin();
        $subscriptionPlan->delete();

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    public function updateStatus(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $this->requireSuperAdmin();
        $subscriptionPlan->update(['is_active' => ! $subscriptionPlan->is_active]);

        $msg = $subscriptionPlan->is_active ? 'Plan activated.' : 'Plan deactivated.';

        return back()->with('success', $msg);
    }

    private function parseFeatures(string $text): array
    {
        return array_values(array_filter(
            array_map('trim', explode("\n", $text)),
            fn ($line) => $line !== ''
        ));
    }

    private function requireSuperAdmin(): void
    {
        abort_unless(auth('web')->user()?->is_super_admin, 403, 'Super-admin access required.');
    }
}
