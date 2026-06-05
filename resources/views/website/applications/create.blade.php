@extends('website.layouts.app')

@section('meta-title', 'Apply - ' . config('app.name'))
@section('meta-description', 'Submit your application to your chosen institution and program.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Apply'],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-paper-plane text-[#4299e1]"></i>
                Application
            </span>
            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Submit Application</h1>
            <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                Applying as <strong>{{ $student->name }}</strong>. Choose your institution and program, then submit your application directly.
                <a href="{{ route('profile.edit') }}" class="text-[#4299e1] hover:text-white transition underline underline-offset-2 text-sm ml-1">Edit Profile</a>
            </p>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                <div class="mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Application Form</h2>
                    <p class="text-gray-600 text-[0.95rem]">Select your institution and program, add a message, and submit.</p>
                </div>

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('website.applications.store') }}" class="space-y-5"
                      x-data="appForm()" x-init="init()">
                    @csrf

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" x-model="institutionId" @change="filterPrograms()" required
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('institution_id') border-red-400 @enderror">
                            <option value="">Select Institution</option>
                            @foreach ($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ (old('institution_id') ?: $selectedInstitutionId) == $inst->id ? 'selected' : '' }}>
                                    {{ $inst->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                        <select name="applicable_type" x-model="applicableType" @change="applicableId = ''" required
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('applicable_type') border-red-400 @enderror">
                            <option value="">Select Type</option>
                            @foreach(\App\Models\Application::APPLICABLE_TYPES as $typeClass => $typeLabel)
                                <option value="{{ $typeClass }}" {{ (old('applicable_type') ?: ($selectedApplicableType ?? '')) === $typeClass ? 'selected' : '' }}>{{ $typeLabel }}</option>
                            @endforeach
                        </select>
                        @error('applicable_type') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Item <span class="text-red-500">*</span></label>
                        <select name="applicable_id" x-model="applicableId" required :disabled="!applicableType || !institutionId"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('applicable_id') border-red-400 @enderror">
                            <option value="" x-text="(!applicableType || !institutionId) ? 'Select institution & type first' : 'Select item'"></option>
                            <template x-for="item in filteredApplicables" :key="item.id">
                                <option :value="item.id" x-text="item.label"></option>
                            </template>
                        </select>
                        @error('applicable_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Scholarship (optional)</label>
                        <select name="scholarship_id"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                            <option value="">No Scholarship</option>
                            @foreach ($scholarships as $sch)
                                <option value="{{ $sch->id }}" {{ (old('scholarship_id') ?: $selectedScholarshipId) == $sch->id ? 'selected' : '' }}>
                                    {{ $sch->title }}
                                    @if ($sch->institution_id) ({{ $sch->institution?->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">How did you hear about us? (optional)</label>
                        <select name="source"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                            @foreach (\App\Models\Application::SOURCES as $key => $label)
                                <option value="{{ $key }}" {{ old('source', 'direct') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Message to Institution (optional)</label>
                        <textarea name="student_message" rows="4" maxlength="2000"
                                  placeholder="Add a personal statement or any additional notes..."
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none">{{ old('student_message') }}</textarea>
                    </div>

                    <div class="bg-[#4299e1]/10 border border-[#4299e1]/20 rounded-xl p-4 text-sm text-[#2c5aa0] flex items-start gap-3">
                        <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                        <span>By submitting this application, you confirm that the information provided is accurate. Your application will be reviewed by the institution.</span>
                    </div>

                    <button type="submit"
                            class="w-full px-6 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Submit Application
                    </button>
                </form>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">What Happens Next?</h3>
                    <div class="space-y-4">
                        @foreach ([
                            ['icon' => 'fa-check', 'title' => 'Application submitted', 'text' => 'Your application is forwarded to the institution with your details and message.'],
                            ['icon' => 'fa-bell', 'title' => 'Under review', 'text' => 'The institution team reviews your application and academic background.'],
                            ['icon' => 'fa-phone', 'title' => 'Institution follow-up', 'text' => 'A representative may contact you to discuss admission or next steps.'],
                        ] as $step)
                            <div class="flex gap-3">
                                <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center flex-shrink-0">
                                    <i class="fas {{ $step['icon'] }}"></i>
                                </span>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $step['title'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $step['text'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Have Questions?</h3>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Unsure about a program or institution? Send an inquiry first before submitting your application.</p>
                    <a href="{{ route('website.inquiry.create') }}"
                       class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-semibold px-5 py-3 rounded-xl hover:bg-gray-100 transition no-underline">
                        <i class="fas fa-question-circle"></i> Submit Inquiry
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

@push('scripts')
<script>
function appForm() {
    return {
        institutionId: @js((string) old('institution_id', $selectedInstitutionId ?? '')),
        applicableType: @js(old('applicable_type', $selectedApplicableType ?? '')),
        applicableId: @js((string) old('applicable_id', $selectedApplicableId ?? '')),
        applicables: @js(collect($applicables)->map(fn($items, $type) => $items->map(fn($item) => ['id' => (string)$item->id, 'institution_id' => (string)$item->institution_id, 'label' => method_exists($item, 'getDisplayNameAttribute') ? $item->display_name : $item->title]))->toArray()),
        get filteredApplicables() {
            if (!this.applicableType || !this.institutionId) return [];
            const items = this.applicables[this.applicableType] ?? [];
            return items.filter(i => i.institution_id === String(this.institutionId));
        },
        init() {
            // x-for renders options asynchronously; reset and re-set applicableId
            // after the next tick so x-model finds the rendered option to select.
            const preselected = this.applicableId;
            if (preselected) {
                this.applicableId = '';
                this.$nextTick(() => { this.applicableId = preselected; });
            }
        },
        filterPrograms() {}
    }
}
</script>
@endpush
@endsection
