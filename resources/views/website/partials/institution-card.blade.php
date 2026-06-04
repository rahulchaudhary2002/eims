{{-- Usage: @include('website.partials.institution-card', ['institution' => $institution]) --}}
<a href="{{ route($institution->type === 'college' ? 'website.colleges.show' : 'website.institutions.show', $institution->slug) }}"
   class="block bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200 no-underline group">
    <div class="relative h-44 overflow-hidden bg-[#f7fafc]">
        @if ($institution->cover_image)
            <img src="{{ Storage::url($institution->cover_image) }}"
                 alt="{{ $institution->name }}"
                 class="w-full h-full object-cover transition-all duration-300 group-hover:scale-105">
        @else
            <div class="w-full h-full bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] flex items-center justify-center">
                <i class="fas fa-university text-white/70 text-5xl"></i>
            </div>
        @endif

        @if ($institution->is_featured)
            <span class="absolute top-3 right-3 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">
                Featured
            </span>
        @endif
    </div>

    <div class="p-7">
        <div class="flex items-start gap-4 mb-4">
            <div class="w-16 h-16 rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden flex-shrink-0">
                @if ($institution->logo)
                    <img src="{{ Storage::url($institution->logo) }}"
                         alt="{{ $institution->name }} logo"
                         class="w-full h-full object-contain p-1.5">
                @else
                    <div class="w-full h-full bg-[#4299e1]/10 text-[#4299e1] flex items-center justify-center">
                        <i class="fas fa-university text-2xl"></i>
                    </div>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <span class="inline-block px-4 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold mb-2 capitalize">
                    {{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}
                </span>

                <h3 class="text-2xl text-[#2c5aa0] leading-tight font-bold line-clamp-2">
                    {{ $institution->name }}
                </h3>
            </div>
        </div>

        @if ($institution->city || $institution->province)
            <div class="text-gray-600 mb-4 flex items-start gap-1.5 text-sm">
                <i class="fas fa-map-marker-alt text-[#4299e1] mt-0.5 flex-shrink-0"></i>
                <span>{{ collect([$institution->city, $institution->province])->filter()->implode(', ') }}</span>
            </div>
        @endif

        <div class="flex justify-between gap-3 pt-4 border-t border-gray-200">
            <div class="text-center">
                <span class="text-lg font-bold text-[#2c5aa0] block">{{ $institution->programs_count ?? $institution->programs()->count() }}</span>
                <span class="text-xs text-gray-600 block">Programs</span>
            </div>
            @php $avg = $institution->reviews_avg_rating ?? null; @endphp
            <div class="text-center">
                <span class="text-lg font-bold text-[#2c5aa0] block">{{ $avg ? number_format($avg, 1) : '-' }}</span>
                <span class="text-xs text-gray-600 block">Rating</span>
            </div>
            <div class="text-center">
                <span class="text-lg font-bold text-[#2c5aa0] block">{{ $institution->established_year ?? '-' }}</span>
                <span class="text-xs text-gray-600 block">Established</span>
            </div>
        </div>
    </div>
</a>
