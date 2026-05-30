{{-- Usage: @include('website.partials.scholarship-card', ['scholarship' => $scholarship]) --}}
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-7 hover:-translate-y-1.5 hover:shadow-2xl transition-all relative overflow-hidden">
    <div class="flex items-start gap-3 mb-3">
        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-award text-yellow-500"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-xl text-[#2c5aa0] font-bold line-clamp-2 mb-1">{{ $scholarship->title }}</h3>
            @if ($scholarship->institution)
                <p class="text-xs text-gray-500 flex items-center gap-1">
                    <i class="fas fa-university text-gray-400"></i>
                    {{ $scholarship->institution->name }}
                </p>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-3">
        <span class="bg-blue-50 text-blue-600 text-xs font-medium px-2 py-0.5 rounded-full">
            {{ \App\Models\Scholarship::TYPES[$scholarship->type] ?? $scholarship->type }}
        </span>
        <span class="bg-green-50 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">
            {{ \App\Models\Scholarship::BENEFIT_TYPES[$scholarship->benefit_type] ?? $scholarship->benefit_type }}
        </span>
    </div>

    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-4">
        <div class="flex items-center gap-1">
            <i class="fas fa-gift text-green-400"></i>
            <span>
                @if ($scholarship->benefit_type === 'percentage')
                    {{ $scholarship->benefit_value }}% off
                @else
                    NPR {{ number_format($scholarship->benefit_value) }}
                @endif
            </span>
        </div>
        @if ($scholarship->total_slots)
            <div class="flex items-center gap-1">
                <i class="fas fa-users text-blue-400"></i>
                <span>{{ $scholarship->total_slots - $scholarship->used_slots }} slots left</span>
            </div>
        @endif
        @if ($scholarship->end_date)
            <div class="flex items-center gap-1 col-span-2">
                <i class="fas fa-calendar-alt text-red-400"></i>
                <span>Deadline: {{ $scholarship->end_date->format('M d, Y') }}</span>
            </div>
        @endif
    </div>

    <a href="{{ route('website.scholarships.show', $scholarship->slug) }}"
       class="block text-center w-full px-6 py-3 bg-[#4299e1] hover:bg-[#2c5aa0] text-white text-sm font-semibold rounded-xl transition-all no-underline">
        View Scholarship
    </a>
</div>
