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

        <form action="{{ route('student.reward-claims.store') }}" method="POST" enctype="multipart/form-data"
              x-data="{
                selectedInstitution: '{{ old('institution_id', '') }}',
                programs: @json($programsByInstitution ?? []),
                docs: [{ type: '', file: null }],
                addDoc() { this.docs.push({ type: '', file: null }) },
                removeDoc(i) { if (this.docs.length > 1) this.docs.splice(i, 1) }
              }"
              class="space-y-6">
            @csrf

            {{-- Section 1: Admission Details --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        Admission Details
                    </h2>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" x-model="selectedInstitution" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                            <option value="">Select Institution</option>
                            @foreach($institutions as $institution)
                                <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Program <span class="text-red-500">*</span></label>
                        <select name="institution_program_id" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                            <option value="">Select Program</option>
                            <template x-if="selectedInstitution && programs[selectedInstitution]">
                                <template x-for="prog in programs[selectedInstitution]" :key="prog.id">
                                    <option :value="prog.id" x-text="prog.title || prog.program_name"></option>
                                </template>
                            </template>
                            {{-- Fallback for old value --}}
                            @foreach($institutionPrograms ?? [] as $ip)
                                <option value="{{ $ip->id }}" {{ old('institution_program_id') == $ip->id ? 'selected' : '' }}>
                                    {{ $ip->title ?: ($ip->program->name ?? 'Program') }}
                                </option>
                            @endforeach
                        </select>
                        @error('institution_program_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Admission Date <span class="text-red-500">*</span></label>
                        <input type="date" name="admission_date" value="{{ old('admission_date') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]" required>
                        @error('admission_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Admission Number <span class="text-red-500">*</span></label>
                        <input type="text" name="admission_number" value="{{ old('admission_number') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]"
                               placeholder="e.g. ADM-2024-001" required>
                        @error('admission_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Intake</label>
                        <input type="text" name="intake" value="{{ old('intake') }}"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#4299e1]/30 focus:border-[#4299e1]"
                               placeholder="e.g. September 2024">
                        @error('intake')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                                        @foreach(\App\Models\StudentRewardClaimDocument::DOCUMENT_TYPES ?? [] as $value => $label)
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
    </div>
</section>

@endsection
