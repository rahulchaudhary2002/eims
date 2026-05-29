{{-- Shared form partial for create & edit --}}
@php
    use App\Models\InstitutionProgram;
    $ip = $institutionProgram ?? null;
    $statusOptions = InstitutionProgram::STATUSES;
@endphp

<div class="eims-card p-6 space-y-6">
    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">
        Basic Info
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Institution --}}
        <div>
            <label class="form-label" for="institution_id">Institution <span class="text-red-500">*</span></label>
            <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
                <option value="">— Select Institution —</option>
                @foreach($institutions as $inst)
                    <option value="{{ $inst->id }}"
                        {{ old('institution_id', $ip?->institution_id ?? $selectedInstitutionId ?? '') == $inst->id ? 'selected' : '' }}>
                        {{ $inst->name }}
                    </option>
                @endforeach
            </select>
            @error('institution_id')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Program --}}
        <div>
            <label class="form-label" for="program_id">Program <span class="text-red-500">*</span></label>
            <select id="program_id" name="program_id" class="form-control @error('program_id') is-invalid @enderror">
                <option value="">— Select Program —</option>
                @foreach($programs as $prog)
                    <option value="{{ $prog->id }}"
                        {{ old('program_id', $ip?->program_id ?? $selectedProgramId ?? '') == $prog->id ? 'selected' : '' }}>
                        {{ $prog->name }}
                    </option>
                @endforeach
            </select>
            @error('program_id')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Title --}}
        <div class="sm:col-span-2">
            <label class="form-label" for="title">Custom Title</label>
            <input type="text" id="title" name="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $ip?->title) }}"
                placeholder="e.g. Bachelor of Science in Computer Engineering (2025 Intake)">
            @error('title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Status --}}
        <div>
            <label class="form-label" for="status">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                @foreach($statusOptions as $val => $label)
                    <option value="{{ $val }}" {{ old('status', $ip?->status ?? 'closed') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Duration --}}
        <div>
            <label class="form-label" for="duration_months">Duration (Months)</label>
            <input type="number" id="duration_months" name="duration_months" min="1" max="600"
                class="form-control @error('duration_months') is-invalid @enderror"
                value="{{ old('duration_months', $ip?->duration_months) }}">
            @error('duration_months') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

{{-- Fees --}}
<div class="eims-card p-6 space-y-5">
    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">
        Fees
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach([
            'admission_fee' => 'Admission Fee',
            'monthly_fee'   => 'Monthly Fee',
            'semester_fee'  => 'Semester Fee',
            'annual_fee'    => 'Annual Fee',
            'total_fee'     => 'Total Fee',
        ] as $field => $label)
        <div>
            <label class="form-label" for="{{ $field }}">{{ $label }}</label>
            <input type="number" id="{{ $field }}" name="{{ $field }}" min="0" step="0.01"
                class="form-control @error($field) is-invalid @enderror"
                value="{{ old($field, $ip?->$field) }}" placeholder="0.00">
            @error($field) <p class="form-error">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</div>

{{-- Seats & Requirements --}}
<div class="eims-card p-6 space-y-5">
    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">
        Seats & Requirements
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div>
            <label class="form-label" for="total_seats">Total Seats</label>
            <input type="number" id="total_seats" name="total_seats" min="0"
                class="form-control @error('total_seats') is-invalid @enderror"
                value="{{ old('total_seats', $ip?->total_seats) }}">
            @error('total_seats') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="available_seats">Available Seats</label>
            <input type="number" id="available_seats" name="available_seats" min="0"
                class="form-control @error('available_seats') is-invalid @enderror"
                value="{{ old('available_seats', $ip?->available_seats) }}">
            @error('available_seats') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="minimum_gpa">Minimum GPA <span class="text-slate-400 text-xs">(0–4)</span></label>
            <input type="number" id="minimum_gpa" name="minimum_gpa" min="0" max="4" step="0.01"
                class="form-control @error('minimum_gpa') is-invalid @enderror"
                value="{{ old('minimum_gpa', $ip?->minimum_gpa) }}" placeholder="e.g. 2.50">
            @error('minimum_gpa') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="minimum_percentage">Minimum % <span class="text-slate-400 text-xs">(0–100)</span></label>
            <input type="number" id="minimum_percentage" name="minimum_percentage" min="0" max="100" step="0.01"
                class="form-control @error('minimum_percentage') is-invalid @enderror"
                value="{{ old('minimum_percentage', $ip?->minimum_percentage) }}" placeholder="e.g. 45.00">
            @error('minimum_percentage') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

{{-- Admission Window --}}
<div class="eims-card p-6 space-y-5">
    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">
        Admission Window
    </h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="admission_start_date">Start Date</label>
            <input type="date" id="admission_start_date" name="admission_start_date"
                class="form-control @error('admission_start_date') is-invalid @enderror"
                value="{{ old('admission_start_date', $ip?->admission_start_date?->format('Y-m-d')) }}">
            @error('admission_start_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label" for="admission_end_date">End Date</label>
            <input type="date" id="admission_end_date" name="admission_end_date"
                class="form-control @error('admission_end_date') is-invalid @enderror"
                value="{{ old('admission_end_date', $ip?->admission_end_date?->format('Y-m-d')) }}">
            @error('admission_end_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
