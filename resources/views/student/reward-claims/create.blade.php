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
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2 mb-6">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($applications->isEmpty())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
                <i class="fas fa-file-signature text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-600 font-semibold">No applications available for reward claim</p>
                <p class="text-gray-400 text-sm mt-1">A reward claim can only be created from an application, and each application can have only one reward claim.</p>
                <a href="{{ route('website.applications.create') }}"
                   class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
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

            {{-- Section 1: Application Selection --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        Select Application
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Your reward claim will use the institution, program, and available admission details linked to the selected application.</p>
                </div>
                <div class="px-6 py-5 space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Application <span class="text-red-500">*</span></label>
                        <select name="application_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                            <option value="">Select Application</option>
                            @foreach($applications as $application)
                                <option value="{{ $application->id }}" {{ (string) old('application_id', $selectedApplicationId) === (string) $application->id ? 'selected' : '' }}>
                                    {{ $application->application_number ?: ('Application #' . $application->id) }}
                                    - {{ $application->institution?->name ?? 'Institution' }}
                                    @if($application->institutionProgram?->title || $application->institutionProgram?->program?->name)
                                        / {{ $application->institutionProgram?->title ?: $application->institutionProgram?->program?->name }}
                                    @endif
                                    / {{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}
                                </option>
                            @endforeach
                        </select>
                        @error('application_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                        One application can have only one reward claim. Institution, program, and available admission details will be attached automatically.
                    </div>
                </div>
            </div>

            {{-- Section 2: Reward Details --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                        Reward Details
                    </h2>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Claimed Reward Amount</label>
                        <input type="number" name="claimed_reward_amount" value="{{ old('claimed_reward_amount') }}"
                               step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]"
                               placeholder="0.00">
                        <p class="text-xs text-gray-400 mt-1">Leave blank if you're unsure of the amount</p>
                        @error('claimed_reward_amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Preferred Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                            <option value="">Select Method</option>
                            @foreach(\App\Models\StudentRewardClaim::PAYMENT_METHODS ?? [] as $value => $label)
                                <option value="{{ $value }}" {{ old('payment_method') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Note to Platform</label>
                        <textarea name="student_note" rows="3"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]"
                                  placeholder="Any additional information you'd like to share...">{{ old('student_note') }}</textarea>
                        @error('student_note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Upload Documents --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">3</span>
                        Upload Documents
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Upload your admission letter and supporting documents. At least one document is required.</p>
                </div>
                <div class="px-6 py-5 space-y-4">

                    <template x-for="(doc, i) in docs" :key="i">
                        <div class="flex items-start gap-3 p-4 border border-gray-200 rounded-xl">
                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Document Type <span class="text-red-500">*</span></label>
                                    <select :name="`documents[${i}][document_type]`" x-model="doc.type"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                                        <option value="">Select Type</option>
                                        @foreach(\App\Models\StudentRewardClaim::DOCUMENT_TYPES as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">File <span class="text-red-500">*</span></label>
                                    <input type="file" :name="`documents[${i}][file]`"
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#ebf8ff] file:text-[#2c5aa0] hover:file:bg-[#bee3f8]"
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
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('documents.0.file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#2c5aa0] text-white font-bold px-8 py-3 rounded-xl hover:bg-[#1a365d] transition text-sm">
                    <i class="fas fa-paper-plane"></i> Submit Claim
                </button>
                <a href="{{ route('student.reward-claims.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 border border-gray-300 px-5 py-3 rounded-xl hover:bg-gray-50 transition no-underline">
                    Cancel
                </a>
            </div>

        </form>
        @endif
    </div>
</section>

@endsection
