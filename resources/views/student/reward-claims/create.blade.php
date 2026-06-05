@extends('layouts.student')

@section('title', 'Claim Your Reward')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reward-claims.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Claim Your Reward</h1>
                <p class="text-white/70 text-sm mt-1">Submit your admission proof to claim your cashback reward</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">

        @if(session('error'))
        <div class="student-form-alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($applications->isEmpty())
            <div class="student-form-card text-center">
                <i class="fas fa-file-signature text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-600 font-semibold">No applications available for reward claim</p>
                <p class="text-gray-400 text-sm mt-1">A reward claim can only be created from an application, and each application can have only one reward claim.</p>
                <a href="{{ route('website.applications.create') }}"
                   class="student-form-btn-primary mt-4">
                    <i class="fas fa-arrow-right"></i> Apply Now
                </a>
            </div>
        @else
        <form action="{{ route('student.reward-claims.store') }}" method="POST" enctype="multipart/form-data"
              x-data="{
                docs: [{ type: '', file: null }],
                addDoc() { this.docs.push({ type: '', file: null }) },
                removeDoc(i) { if (this.docs.length > 1) this.docs.splice(i, 1) }
              }"
              class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="student-form-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Section 1: Application Selection --}}
            <div class="student-form-panel">
                <div class="student-form-panel-head">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        Select Application
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Your reward claim will use the institution, program, and available admission details linked to the selected application.</p>
                </div>
                <div class="student-form-panel-body space-y-5">
                    <div>
                        <label class="student-form-label">Application <span class="text-red-500">*</span></label>
                        <select name="application_id" class="student-form-control student-form-select" required>
                            <option value="">Select Application</option>
                            @foreach($applications as $application)
                                <option value="{{ $application->id }}" {{ (string) old('application_id', $selectedApplicationId) === (string) $application->id ? 'selected' : '' }}>
                                    {{ $application->application_number ?: ('Application #' . $application->id) }}
                                    - {{ $application->institution?->name ?? 'Institution' }}
                                    @if($application->applicable_label)
                                        / {{ $application->applicable_label }}
                                    @endif
                                    / {{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}
                                </option>
                            @endforeach
                        </select>
                        @error('application_id')
                            <p class="student-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="student-form-info">
                        <i class="fas fa-circle-info mt-0.5"></i>
                        One application can have only one reward claim. Institution, program, and available admission details will be attached automatically.
                    </div>
                </div>
            </div>

            {{-- Section 2: Reward Details --}}
            <div class="student-form-panel">
                <div class="student-form-panel-head">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                        Reward Details
                    </h2>
                </div>
                <div class="student-form-panel-body grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="student-form-label">Claimed Reward Amount</label>
                        <input type="number" name="claimed_reward_amount" value="{{ old('claimed_reward_amount') }}"
                               step="0.01" min="0"
                               class="student-form-control"
                               placeholder="0.00">
                        <p class="student-form-help">Leave blank if you're unsure of the amount</p>
                        @error('claimed_reward_amount')
                            <p class="student-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="student-form-label">Preferred Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="student-form-control student-form-select" required>
                            <option value="">Select Method</option>
                            @foreach(\App\Models\StudentRewardClaim::PAYMENT_METHODS ?? [] as $value => $label)
                                <option value="{{ $value }}" {{ old('payment_method') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="student-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="student-form-label">Note to Platform</label>
                        <textarea name="student_note" rows="3"
                                  class="student-form-control student-form-textarea"
                                  placeholder="Any additional information you'd like to share...">{{ old('student_note') }}</textarea>
                        @error('student_note')
                            <p class="student-form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Upload Documents --}}
            <div class="student-form-panel">
                <div class="student-form-panel-head">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">3</span>
                        Upload Documents
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Upload your admission letter and supporting documents. At least one document is required.</p>
                </div>
                <div class="student-form-panel-body space-y-4">

                    <template x-for="(doc, i) in docs" :key="i">
                        <div class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="student-form-label">Document Type <span class="text-red-500">*</span></label>
                                    <select :name="`documents[${i}][document_type]`" x-model="doc.type"
                                            class="student-form-control student-form-select" required>
                                        <option value="">Select Type</option>
                                        @foreach(\App\Models\StudentRewardClaim::DOCUMENT_TYPES as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="student-form-label">File <span class="text-red-500">*</span></label>
                                    <input type="file" :name="`documents[${i}][file]`"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           class="student-form-file"
                                           required>
                                </div>
                            </div>
                            <button type="button" @click="removeDoc(i)" x-show="docs.length > 1"
                                    class="mt-6 shrink-0 w-8 h-8 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 flex items-center justify-center transition">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="addDoc()"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-[#4299e1] border border-[#bee3f8] bg-[#ebf8ff] px-4 py-2 rounded-xl hover:bg-[#bee3f8] transition">
                        <i class="fas fa-plus text-xs"></i> Add Another Document
                    </button>

                    @error('documents')
                        <p class="student-form-error">{{ $message }}</p>
                    @enderror
                    @error('documents.0.file')
                        <p class="student-form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="student-form-actions justify-start">
                <button type="submit"
                        class="student-form-btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Claim
                </button>
                <a href="{{ route('student.reward-claims.index') }}"
                   class="student-form-btn-secondary">
                    Cancel
                </a>
            </div>

        </form>
        @endif
    </div>
</section>

@endsection
