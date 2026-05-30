@extends('admin.layouts.app')
@section('title', $subscriptionPlan->name)
@section('page-title', 'Subscription Plan Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="{{ $subscriptionPlan->name }}"
        subtitle="Subscription Plan"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Subscription Plans', 'route' => 'admin.subscription-plans.index'],
            ['label' => $subscriptionPlan->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Plan Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Plan Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Name</dt>
                        <dd class="mt-1 font-semibold text-slate-800 text-base">{{ $subscriptionPlan->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Slug</dt>
                        <dd class="mt-1 font-mono text-slate-700">{{ $subscriptionPlan->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Monthly Price</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800 text-lg">
                            {{ number_format((float) $subscriptionPlan->price_monthly, 2) }}
                            <span class="text-xs font-normal text-slate-400">/ mo</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Yearly Price</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800 text-lg">
                            {{ number_format((float) $subscriptionPlan->price_yearly, 2) }}
                            <span class="text-xs font-normal text-slate-400">/ yr</span>
                        </dd>
                    </div>
                    @if($subscriptionPlan->price_monthly > 0)
                        <div>
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Yearly Savings</dt>
                            <dd class="mt-1 font-mono text-green-600">
                                @php $savings = ((float) $subscriptionPlan->price_monthly * 12) - (float) $subscriptionPlan->price_yearly; @endphp
                                {{ $savings > 0 ? number_format($savings, 2) . ' saved' : '-' }}
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $subscriptionPlan->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Features --}}
            @if(! empty($subscriptionPlan->features))
                <div class="eims-card p-6">
                    <h3 class="text-base font-semibold text-slate-800 mb-4">Features</h3>
                    <ul class="space-y-2">
                        @foreach($subscriptionPlan->features as $feature)
                            <li class="flex items-start gap-2 text-sm text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge {{ $subscriptionPlan->is_active ? 'badge-green' : '' }} text-sm">
                    {{ $subscriptionPlan->is_active ? 'Active' : 'Inactive' }}
                </span>
                <form action="{{ route('admin.subscription-plans.update-status', $subscriptionPlan) }}" method="POST" class="mt-3">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn {{ $subscriptionPlan->is_active ? 'btn-secondary' : 'btn-primary' }} w-full text-sm">
                        {{ $subscriptionPlan->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Plan
                    </a>
                    <form action="{{ route('admin.subscription-plans.destroy', $subscriptionPlan) }}" method="POST" onsubmit="return confirm('Delete this plan? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Plan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Subscribed Institutions --}}
    <div class="eims-card overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-slate-800">Subscribed Institutions</h3>
                <span class="text-xs text-slate-400">{{ $subscriptionPlan->institutionSubscriptions->count() }} subscription(s)</span>
            </div>
            <a href="{{ route('admin.institution-subscriptions.create', ['subscription_plan_id' => $subscriptionPlan->id]) }}" class="btn btn-primary text-xs py-1.5">Add</a>
        </div>

        @if($subscriptionPlan->institutionSubscriptions->isEmpty())
            <div class="px-6 py-8 text-center text-slate-400 text-sm">No institutions subscribed to this plan yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Cycle</th>
                            <th>Amount</th>
                            <th>Starts</th>
                            <th>Ends</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptionPlan->institutionSubscriptions as $sub)
                            <tr>
                                <td>
                                    @if($sub->institution)
                                        <a href="{{ route('admin.institutions.show', $sub->institution) }}" class="font-medium text-blue-600 hover:underline text-sm">{{ $sub->institution->name }}</a>
                                    @else <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm">{{ \App\Models\InstitutionSubscription::BILLING_CYCLES[$sub->billing_cycle] ?? $sub->billing_cycle }}</td>
                                <td class="font-mono text-sm">{{ number_format((float) $sub->amount, 2) }}</td>
                                <td class="text-xs text-slate-500">{{ $sub->starts_at?->format('d M Y') }}</td>
                                <td class="text-xs text-slate-500">{{ $sub->ends_at?->format('d M Y') ?? 'Ongoing' }}</td>
                                <td><span class="badge">{{ \App\Models\InstitutionSubscription::STATUSES[$sub->status] ?? $sub->status }}</span></td>
                                <td>
                                    <div class="flex items-center justify-center">
                                        <a href="{{ route('admin.institution-subscriptions.show', $sub) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.institution-subscriptions.index', ['subscription_plan_id' => $subscriptionPlan->id]) }}" class="text-sm text-blue-600 hover:underline">
                    View all subscriptions for this plan →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
