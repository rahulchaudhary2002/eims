@extends('admin.layouts.app')
@section('title', $referral->referral_number)
@section('page-title', 'Referral Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$referral->referral_number"
        :subtitle="$referral->student->name ?? 'Application Referral'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Application Referrals','route' => 'admin.application-referrals.index'],
            ['label'=>$referral->referral_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.application-referrals.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left Column --}}
        <div class="space-y-5">

            {{-- Status Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Status</h3>
                <form action="{{ route('admin.application-referrals.update-status', $referral) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control">
                        @foreach(\App\Models\ApplicationReferral::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $referral->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea name="remarks" rows="3" class="form-control" placeholder="Status change remarks"></textarea>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            {{-- Timeline Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Timeline</h3>
                <dl class="space-y-3 text-sm">
                    @foreach([
                        'referred_at' => 'Referred At',
                        'preview_viewed_at' => 'Preview Viewed',
                        'profile_unlocked_at' => 'Profile Unlocked',
                        'agreement_accepted_at' => 'Agreement Accepted',
                        'protection_starts_at' => 'Protection Starts',
                        'protection_expires_at' => 'Protection Expires',
                    ] as $field => $label)
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">{{ $label }}</dt>
                            <dd class="text-right text-xs">{{ $referral->$field?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            {{-- Platform Note Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Platform Note</h3>
                <form action="{{ route('admin.application-referrals.platform-note', $referral) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <textarea name="platform_note" rows="4" class="form-control" placeholder="Internal platform note...">{{ $referral->platform_note }}</textarea>
                    <button type="submit" class="btn btn-primary w-full">Save Note</button>
                </form>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Application Info Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Application Info</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Application Number</dt>
                        <dd>
                            @if($referral->application)
                                <a href="{{ route('admin.applications.show', $referral->application) }}" class="text-blue-600 hover:underline font-semibold">{{ $referral->application->application_number }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Student</dt>
                        <dd>
                            @if($referral->student)
                                <a href="{{ route('admin.students.show', $referral->student) }}" class="text-blue-600 hover:underline">{{ $referral->student->name }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd>
                            @if($referral->institution)
                                <a href="{{ route('admin.institutions.show', $referral->institution) }}" class="text-blue-600 hover:underline">{{ $referral->institution->name }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Program</dt>
                        <dd>{{ $referral->institutionProgram?->title ?: ($referral->institutionProgram?->program?->name ?? '-') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Application Status</dt>
                        <dd>
                            @if($referral->application)
                                <span class="badge">{{ \App\Models\Application::STATUSES[$referral->application->status] ?? $referral->application->status }}</span>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Student Profile Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Student Profile</h3>
                    @if($referral->is_profile_unlocked)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Profile Unlocked
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Profile Locked
                        </span>
                    @endif
                </div>

                @if(!$referral->is_profile_unlocked)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4">
                        <p class="text-amber-800 text-sm font-medium">Profile not yet unlocked by institution.</p>
                        <p class="text-amber-700 text-xs mt-1">The institution must unlock this profile to view full student details.</p>
                    </div>
                    @if($referral->student)
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><dt class="text-slate-400 text-xs mb-1">Name (masked)</dt><dd class="text-slate-600">{{ substr($referral->student->name, 0, 1) }}*** {{ substr($referral->student->name, strrpos($referral->student->name, ' ') + 1, 1) }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Country</dt><dd>{{ $referral->student->profile?->country ?? '-' }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Education Level</dt><dd>{{ $referral->student->profile?->education_level ?? '-' }}</dd></div>
                            <div>
                                <dt class="text-slate-400 text-xs mb-1">Platform Verified</dt>
                                <dd>
                                    @if($referral->student->is_verified ?? false)
                                        <span class="badge bg-green-100 text-green-700">Verified</span>
                                    @else
                                        <span class="badge bg-gray-100 text-gray-600">Not Verified</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    @endif
                @else
                    @if($referral->student)
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div><dt class="text-slate-400 text-xs mb-1">Full Name</dt><dd class="font-medium">{{ $referral->student->name }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Email</dt><dd>{{ $referral->student->email }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Phone</dt><dd>{{ $referral->student->phone ?? '-' }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Country</dt><dd>{{ $referral->student->profile?->country ?? '-' }}</dd></div>
                            <div><dt class="text-slate-400 text-xs mb-1">Education Level</dt><dd>{{ $referral->student->profile?->education_level ?? '-' }}</dd></div>
                            <div>
                                <dt class="text-slate-400 text-xs mb-1">Platform Verified</dt>
                                <dd>
                                    @if($referral->student->is_verified ?? false)
                                        <span class="badge bg-green-100 text-green-700">Verified</span>
                                    @else
                                        <span class="badge bg-gray-100 text-gray-600">Not Verified</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    @endif
                @endif
            </div>

            {{-- Access Logs Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Access Logs</h3>
                @if(isset($accessLogs) && $accessLogs->count())
                    <div class="overflow-x-auto">
                        <table class="eims-table">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>User</th>
                                    <th>IP Address</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accessLogs as $log)
                                    <tr>
                                        <td class="text-xs">{{ $log->action }}</td>
                                        <td class="text-xs">{{ $log->user->name ?? 'System' }}</td>
                                        <td class="text-xs font-mono">{{ $log->ip_address ?? '-' }}</td>
                                        <td class="text-xs text-slate-500">{{ $log->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-400 text-sm">No access logs recorded yet.</p>
                @endif
            </div>

            {{-- Admission Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Admission</h3>
                    @if($referral->admission)
                        <a href="{{ route('admin.admissions.show', $referral->admission) }}" class="text-xs text-indigo-600 hover:underline">View admission</a>
                    @endif
                </div>
                @if($referral->admission)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Number</dt><dd>{{ $referral->admission->admission_number }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Date</dt><dd>{{ $referral->admission->admission_date?->format('d M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Verification</dt><dd>{{ \App\Models\Admission::VERIFICATION_STATUSES[$referral->admission->verification_status] ?? $referral->admission->verification_status }}</dd></div>
                    </dl>
                @else
                    <p class="text-slate-400 text-sm">No admission created yet.</p>
                @endif
            </div>

            {{-- Reward Claims Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Reward Claims</h3>
                @if(isset($rewardClaims) && $rewardClaims->count())
                    <div class="space-y-3">
                        @foreach($rewardClaims as $claim)
                            <div class="border border-slate-100 rounded-lg p-3 flex items-center justify-between">
                                <div>
                                    <a href="{{ route('admin.student-reward-claims.show', $claim) }}" class="text-sm font-semibold text-blue-600 hover:underline">{{ $claim->claim_number }}</a>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $claim->submitted_at?->format('d M Y') ?? '-' }}</div>
                                </div>
                                <span class="badge">{{ \App\Models\StudentRewardClaim::STATUSES[$claim->status] ?? $claim->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400 text-sm">No reward claims linked to this referral.</p>
                @endif
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <form action="{{ route('admin.application-referrals.destroy', $referral) }}" method="POST" onsubmit="return confirm('Delete this referral? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Referral</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
