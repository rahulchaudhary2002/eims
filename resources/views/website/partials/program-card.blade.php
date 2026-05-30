{{-- Usage: @include('website.partials.program-card', ['program' => $institutionProgram]) --}}
@php
    $inst    = $program->institution ?? null;
    $prog    = $program->program ?? null;
    $faculty = $prog?->faculty ?? null;
@endphp
<div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-200 transition-all hover:-translate-y-1.5 hover:shadow-2xl">
    <div class="p-7 pb-4 border-b border-gray-200">
        <div class="flex items-start justify-between mb-3">
        <div class="flex-1 min-w-0">
            <span class="inline-block px-4 py-1.5 bg-[#4299e1]/10 text-[#4299e1] rounded-full text-xs font-semibold mb-4">
                {{ $faculty?->name ?? 'General' }}
            </span>
            <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold line-clamp-2">
                {{ $program->display_name }}
            </h3>
        </div>
        @php
            $statusColors = ['open' => 'bg-green-100 text-green-700', 'upcoming' => 'bg-yellow-100 text-yellow-700', 'closed' => 'bg-red-100 text-red-700', 'suspended' => 'bg-gray-100 text-gray-600'];
        @endphp
        <span class="ml-2 flex-shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full {{ $statusColors[$program->status] ?? 'bg-gray-100 text-gray-600' }}">
            {{ \App\Models\InstitutionProgram::STATUSES[$program->status] ?? $program->status }}
        </span>
        </div>

    @if ($inst)
        <div class="text-gray-600 flex items-center gap-1.5 text-sm">
            <i class="fas fa-university text-[#4299e1]"></i>
            <span class="truncate">{{ $inst->name }}</span>
        </div>
    @endif
    </div>

    <div class="p-5 pt-5">
    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-5">
        @if ($program->total_fee)
            <div class="flex items-center gap-1">
                <i class="fas fa-money-bill-wave text-green-400"></i>
                <span>NPR {{ number_format($program->total_fee) }}</span>
            </div>
        @endif
        @if ($program->duration_months)
            <div class="flex items-center gap-1">
                <i class="fas fa-clock text-blue-400"></i>
                <span>{{ $program->duration_months }} months</span>
            </div>
        @endif
        @if ($program->available_seats)
            <div class="flex items-center gap-1">
                <i class="fas fa-chair text-purple-400"></i>
                <span>{{ $program->available_seats }} seats</span>
            </div>
        @endif
        @if ($program->admission_end_date)
            <div class="flex items-center gap-1">
                <i class="fas fa-calendar text-orange-400"></i>
                <span>{{ $program->admission_end_date->format('M d, Y') }}</span>
            </div>
        @endif
    </div>

    <div class="flex gap-2 justify-end">
        <a href="{{ route('website.institutions.programs.show', [$inst?->slug, $program->slug]) }}"
           class="text-center px-4 py-2 text-sm font-semibold text-[#4299e1] border-2 border-[#4299e1] rounded-xl hover:bg-[#4299e1]/10 transition-all no-underline">
            Details
        </a>
        <a href="{{ route('website.applications.create', ['institution' => $inst?->slug, 'program' => $program->slug]) }}"
           class="text-center px-4 py-2 text-sm font-semibold text-white bg-[#4299e1] rounded-xl hover:bg-[#2c5aa0] hover:-translate-y-1 transition-all no-underline">
            Apply
        </a>
    </div>
    </div>
</div>
