@extends('institution.layouts.app')
@section('title', $referral->referral_number)
@section('page-title', 'Referral Details')

@section('content')
<div class="space-y-5" x-data="{ unlockModalOpen: false }">
    <x-admin.page-header
        :title="$referral->referral_number"
        subtitle="Referred Application"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'institution.dashboard'],
            ['label'=>'Referred Applications','route'=>'institution.referrals.index'],
            ['label'=>$referral->referral_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('institution.referrals.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    @php $masked = !$referral->is_profile_unlocked; @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left Column --}}
        <div class="space-y-5">

            {{-- Status Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-3">Status</h3>
                <div class="mb-4">
                    <span class="badge text-sm">{{ \App\Models\ApplicationReferral::STATUSES[$referral->status] ?? $referral->status }}</span>
                </div>
                @if(!in_array($referral->status, ['accepted', 'rejected']))
                    <div class="space-y-2">
                        <form action="{{ route('institution.referrals.accept', $referral) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-primary w-full">Accept Referral</button>
                        </form>
                        <form action="{{ route('institution.referrals.reject', $referral) }}" method="POST" onsubmit="return confirm('Reject this referral?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger w-full">Reject Referral</button>
                        </form>
                        <form action="{{ route('institution.referrals.request-info', $referral) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-secondary w-full">Request More Info</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Profile Unlock Card --}}
            @if($masked)
                <div class="eims-card p-6 border border-amber-200 bg-amber-50">
                    <h3 class="font-semibold text-amber-800 text-sm uppercase tracking-wide mb-3">Profile Locked</h3>
                    <p class="text-amber-700 text-sm mb-4">
                        Unlock the student profile to see full contact details, documents, and begin the referral protection period.
                    </p>
                    <button @click="unlockModalOpen = true" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                        Unlock Profile
                    </button>
                </div>
            @else
                <div class="eims-card p-6 border border-green-200 bg-green-50">
                    <h3 class="font-semibold text-green-800 text-sm uppercase tracking-wide mb-2">Profile Unlocked</h3>
                    <p class="text-green-700 text-sm">
                        Profile unlocked on {{ $referral->profile_unlocked_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                    @if($referral->protection_expires_at)
                        <p class="text-green-600 text-xs mt-1">Protection expires: {{ $referral->protection_expires_at->format('d M Y') }}</p>
                    @endif
                </div>
            @endif

            {{-- Timeline Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Timeline</h3>
                <dl class="space-y-3 text-sm">
                    @foreach([
                        'referred_at' => 'Referred At',
                        'viewed_at' => 'Preview Viewed',
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
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Student Profile Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Student Profile</h3>
                    @if($masked)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Preview Only
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            Full Access
                        </span>
                    @endif
                </div>

                @if($masked)
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4 text-sm text-amber-800">
                        <strong>Full student profile is locked.</strong> Unlock to see contact details, full name, documents, and to start the referral protection period.
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @php
                            $parts = explode(' ', $referral->student->name ?? '');
                            $maskedName = (substr($parts[0] ?? '', 0, 1) . '***') . (isset($parts[1]) ? ' ' . substr($parts[1], 0, 1) : '');
                        @endphp
                        <div><dt class="text-slate-400 text-xs mb-1">Name</dt><dd class="text-slate-600 font-medium">{{ $maskedName }}</dd></div>
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
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Documents</dt>
                            <dd class="text-slate-500 text-xs italic">Unlock to view documents</dd>
                        </div>
                    </dl>
                @else
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-400 text-xs mb-1">Full Name</dt><dd class="font-medium">{{ $referral->student->name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Email</dt><dd>{{ $referral->student->email ?? '-' }}</dd></div>
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

                    @if(isset($referral->student->documents) && $referral->student->documents->count())
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Student Documents</h4>
                            <div class="space-y-2">
                                @foreach($referral->student->documents as $doc)
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-700">{{ $doc->document_type ?? 'Document' }}</span>
                                        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline text-xs">View</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Application Info Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Application Info</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-400 text-xs mb-1">Program</dt><dd>{{ $referral->institutionProgram?->title ?: ($referral->institutionProgram?->program?->name ?? '-') }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Application Status</dt><dd><span class="badge">{{ \App\Models\Application::STATUSES[$referral->application?->status] ?? ($referral->application?->status ?? '-') }}</span></dd></div>
                    @if($referral->application?->student_message)
                        <div class="md:col-span-2">
                            <dt class="text-slate-400 text-xs mb-1">Student Message</dt>
                            <dd class="text-slate-700 whitespace-pre-line">{{ $referral->application->student_message }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Referral Info Card --}}
            @if(!$masked)
                <div class="eims-card p-6">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Referral Info</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-400 text-xs mb-1">Referred At</dt><dd>{{ $referral->referred_at?->format('d M Y, H:i') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Protection Starts</dt><dd>{{ $referral->protection_starts_at?->format('d M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Protection Expires</dt><dd>{{ $referral->protection_expires_at?->format('d M Y') ?? '-' }}</dd></div>
                    </dl>
                </div>
            @endif
        </div>
    </div>

    {{-- Unlock Profile Modal --}}
    <div x-show="unlockModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/50" @click="unlockModalOpen = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-start gap-4 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-slate-800">Unlock Student Profile</h3>
                    <p class="text-slate-500 text-sm mt-0.5">Please read the terms carefully before proceeding.</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-5 text-sm text-amber-800">
                By unlocking this profile, your institution agrees that this student is protected under the referral agreement. If this student is admitted to your institution within the protection period, commission may be payable to the platform, even if this referral is later rejected.
            </div>

            <div class="flex gap-3">
                <button @click="unlockModalOpen = false" class="btn btn-secondary flex-1">Cancel</button>
                <form action="{{ route('institution.referrals.request-unlock', $referral) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="terms_accepted" value="1">
                    <button type="submit" class="btn btn-primary w-full">Accept &amp; Unlock Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
