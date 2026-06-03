@extends('admin.layouts.app')
@section('title', $claim->claim_number)
@section('page-title', 'Reward Claim Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$claim->claim_number"
        :subtitle="$claim->student->name ?? 'Reward Claim'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Reward Claims','route' => 'admin.student-reward-claims.index'],
            ['label'=>$claim->claim_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-reward-claims.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Left Column --}}
        <div class="space-y-5">

            {{-- Status Update Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Update Status</h3>
                <form action="{{ route('admin.student-reward-claims.update-status', $claim) }}" method="POST" class="space-y-3" x-data="{ status: '{{ $claim->status }}' }">
                    @csrf @method('PATCH')
                    <select name="status" x-model="status" class="form-control">
                        @foreach(\App\Models\StudentRewardClaim::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $claim->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div x-show="status === 'approved'" x-cloak>
                        <label class="form-label text-xs">Approved Reward Amount</label>
                        <input type="number" name="approved_reward_amount" step="0.01" min="0"
                               value="{{ $claim->approved_reward_amount }}"
                               class="form-control" placeholder="0.00">
                    </div>

                    <div x-show="status === 'rejected'" x-cloak>
                        <label class="form-label text-xs">Rejection Reason</label>
                        <textarea name="rejection_reason" rows="3" class="form-control" placeholder="Reason for rejection...">{{ $claim->rejection_reason }}</textarea>
                    </div>

                    <div x-show="status === 'paid'" x-cloak class="space-y-3">
                        <div>
                            <label class="form-label text-xs">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="">Select Method</option>
                                @foreach(\App\Models\StudentRewardClaim::PAYMENT_METHODS ?? [] as $value => $label)
                                    <option value="{{ $value }}" {{ $claim->payment_method === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label text-xs">Transaction Reference</label>
                            <input type="text" name="transaction_reference" class="form-control" placeholder="Transaction ID or reference" value="{{ $claim->transaction_reference ?? '' }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            {{-- Action Buttons Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.student-reward-claims.documents', $claim) }}" class="btn btn-secondary w-full text-center">
                        Verify Documents
                    </a>

                    <div>
                        <label class="form-label text-xs mb-1">Link Referral</label>
                        <form action="{{ route('admin.student-reward-claims.link-referral', $claim) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="referral_id" class="form-control flex-1">
                                <option value="">Select Referral</option>
                                @foreach($availableReferrals ?? [] as $referral)
                                    <option value="{{ $referral->id }}" {{ $claim->referral_id == $referral->id ? 'selected' : '' }}>
                                        {{ $referral->referral_number }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary shrink-0">Link</button>
                        </form>
                    </div>

                    <div>
                        <label class="form-label text-xs mb-1">Link Admission</label>
                        <form action="{{ route('admin.student-reward-claims.link-admission', $claim) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="admission_id" class="form-control flex-1">
                                <option value="">Select Admission</option>
                                @foreach($availableAdmissions ?? [] as $admission)
                                    <option value="{{ $admission->id }}" {{ $claim->admission_id == $admission->id ? 'selected' : '' }}>
                                        {{ $admission->admission_number }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary shrink-0">Link</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Claim Info Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Claim Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-400 text-xs mb-1">Claim Number</dt><dd class="font-semibold">{{ $claim->claim_number }}</dd></div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Status</dt>
                        <dd><span class="badge">{{ \App\Models\StudentRewardClaim::STATUSES[$claim->status] ?? $claim->status }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Student</dt>
                        <dd>
                            @if($claim->student)
                                <a href="{{ route('admin.students.show', $claim->student) }}" class="text-blue-600 hover:underline">{{ $claim->student->name }}</a>
                            @else -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd>
                            @if($claim->institution)
                                <a href="{{ route('admin.institutions.show', $claim->institution) }}" class="text-blue-600 hover:underline">{{ $claim->institution->name }}</a>
                            @else -
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-slate-400 text-xs mb-1">Program</dt><dd>{{ $claim->institutionProgram?->title ?: ($claim->institutionProgram?->program?->name ?? '-') }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Admission Date</dt><dd>{{ $claim->admission_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Admission Number</dt><dd>{{ $claim->admission_number ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Intake</dt><dd>{{ $claim->intake ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Claimed Amount</dt><dd class="font-mono">{{ $claim->claimed_reward_amount !== null ? number_format((float) $claim->claimed_reward_amount, 2) : '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Approved Amount</dt><dd class="font-mono">{{ $claim->approved_reward_amount !== null ? number_format((float) $claim->approved_reward_amount, 2) : '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Payment Method</dt><dd>{{ \App\Models\StudentRewardClaim::PAYMENT_METHODS[$claim->payment_method] ?? ($claim->payment_method ?? '-') }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Submitted At</dt><dd>{{ $claim->submitted_at?->format('d M Y, H:i') ?? '-' }}</dd></div>
                </dl>
            </div>

            {{-- Documents Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Documents</h3>
                @if(isset($claim->documents) && $claim->documents->count())
                    <div class="space-y-3">
                        @foreach($claim->documents as $document)
                            <div class="border border-slate-100 rounded-lg p-3 flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-slate-800">{{ \App\Models\StudentRewardClaimDocument::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type }}</div>
                                    <div class="text-xs text-slate-400 truncate mt-0.5">{{ $document->original_name }}</div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($document->is_verified)
                                        <span class="text-xs font-medium text-green-700 bg-green-50 border border-green-200 rounded-full px-2 py-0.5">Verified</span>
                                    @else
                                        <form action="{{ route('admin.student-reward-claims.verify-document', [$claim, $document]) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-full px-2 py-0.5 hover:bg-blue-100 transition">Verify</button>
                                        </form>
                                    @endif
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" rel="noopener" class="btn-icon btn-icon-view" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400 text-sm">No documents uploaded.</p>
                @endif
            </div>

            {{-- Notes Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Notes</h3>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Student Note</dt>
                        <dd class="text-slate-700 whitespace-pre-line">{{ $claim->student_note ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Admin Note</dt>
                        <dd class="text-slate-700 whitespace-pre-line">{{ $claim->admin_note ?: '-' }}</dd>
                    </div>
                    @if($claim->rejection_reason)
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Rejection Reason</dt>
                            <dd class="text-red-700 whitespace-pre-line">{{ $claim->rejection_reason }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Linked Referral Card --}}
            @if($claim->referral)
                <div class="eims-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Linked Referral</h3>
                        <a href="{{ route('admin.referrals.show', $claim->referral) }}" class="text-xs text-indigo-600 hover:underline">View referral</a>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Referral Number</dt>
                            <dd><a href="{{ route('admin.referrals.show', $claim->referral) }}" class="text-blue-600 hover:underline font-semibold">{{ $claim->referral->referral_number }}</a></dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Referred At</dt>
                            <dd>{{ $claim->referral->referred_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Status</dt>
                            <dd><span class="badge">{{ \App\Models\ApplicationReferral::STATUSES[$claim->referral->status] ?? $claim->referral->status }}</span></dd>
                        </div>
                    </dl>
                </div>
            @endif

            {{-- Linked Admission Card --}}
            @if($claim->admission)
                <div class="eims-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Linked Admission</h3>
                        <a href="{{ route('admin.admissions.show', $claim->admission) }}" class="text-xs text-indigo-600 hover:underline">View admission</a>
                    </div>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Number</dt><dd class="font-semibold">{{ $claim->admission->admission_number }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Date</dt><dd>{{ $claim->admission->admission_date?->format('d M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Verification</dt><dd>{{ \App\Models\Admission::VERIFICATION_STATUSES[$claim->admission->verification_status] ?? $claim->admission->verification_status }}</dd></div>
                    </dl>
                </div>
            @endif

            {{-- Payment History Card --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Payment History</h3>
                @if(isset($payments) && $payments->count())
                    <div class="overflow-x-auto">
                        <table class="eims-table">
                            <thead>
                                <tr>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td class="font-mono text-sm">{{ number_format((float) $payment->amount, 2) }}</td>
                                        <td class="text-xs">{{ $payment->payment_method ?? '-' }}</td>
                                        <td class="text-xs font-mono">{{ $payment->transaction_reference ?? '-' }}</td>
                                        <td class="text-xs text-slate-500">{{ $payment->paid_at?->format('d M Y, H:i') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-slate-400 text-sm">No payments recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
