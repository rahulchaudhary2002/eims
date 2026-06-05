@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- ── Page Header ── --}}
    <x-admin.page-header
        title="Dashboard"
        subtitle="Welcome back - here's what's happening today.">
        <x-slot:actions>
            <a href="{{ route('admin.institutions.create') }}" class="btn btn-primary btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Institution
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- ── Summary Stats ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="stat-card flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Students</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($studentCount) }}</p>
            </div>
        </div>

        <div class="stat-card flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-sky-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Institutions</p>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($institutionCount) }}</p>
            </div>
        </div>

        <div class="stat-card flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-success-light flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Revenue</p>
                <p class="text-2xl font-bold text-slate-900">NPR {{ number_format($receivedComission, 0) }}</p>
            </div>
        </div>

        <div class="stat-card flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-warning-light flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Pending Commissions</p>
                <p class="text-2xl font-bold text-slate-900">NPR {{ number_format($pendingComission, 0) }}</p>
            </div>
        </div>
    </div>

    {{-- ── Recent Applications ── --}}
    <div class="eims-card p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Recent Applications</h2>
            <a href="{{ route('admin.applications.index') }}" class="text-sm text-primary-600 font-medium hover:underline">View All</a>
        </div>
        <div class="eims-table-wrapper">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Institution</th>
                        <th>Email</th>
                        <th class="hidden md:table-cell">Phone</th>
                        <th class="hidden lg:table-cell">Course</th>
                        <th class="hidden sm:table-cell">Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentApplications as $application)
                    <tr>
                        <td class="font-medium text-slate-800">{{ $application->student->name ?? $application->student->full_name ?? '-' }}</td>
                        <td class="text-slate-600">{{ $application->institution->name ?? '-' }}</td>
                        <td class="text-slate-600">{{ $application->student->email ?? '-' }}</td>
                        <td class="hidden md:table-cell text-slate-600">{{ $application->student->phone ?? '-' }}</td>
                        <td class="hidden lg:table-cell text-slate-600">{{ $application->applicable_label }}</td>
                        <td class="hidden sm:table-cell text-slate-500 text-xs">{{ $application->created_at->format('M d, Y') }}</td>
                        <td><x-admin.status-badge :status="$application->status ?? 'pending'" /></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-admin.empty-state title="No recent applications" description="No one has applied for admission yet." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
