@extends('layouts.student')

@section('title', 'Compare')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Compare</h1>
                <p class="text-white/70 text-sm mt-1">{{ $items->count() }}/4 items · Compare institutions and programs side by side</p>
            </div>
            @if($items->count())
            <form method="POST" action="{{ route('student.compare.destroy-all') }}" onsubmit="return confirm('Clear entire compare list?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-white/70 hover:text-white border border-white/30 px-4 py-2 rounded-xl transition">Clear All</button>
            </form>
            @endif
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-6">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @if($items->count() >= 2)
        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 w-40">Feature</th>
                        @foreach($items as $item)
                        <th class="px-5 py-3 text-left min-w-[200px]">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $item->institution?->name }}</p>
                                    @if($item->institutionProgram)
                                    <p class="text-xs text-gray-500 font-normal mt-0.5">{{ $item->institutionProgram?->program?->name }}</p>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('student.compare.destroy', $item) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-400 transition shrink-0">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach([
                        ['Type', fn($i) => \App\Models\Institution::TYPES[$i->institution?->type] ?? $i->institution?->type],
                        ['Location', fn($i) => implode(', ', array_filter([$i->institution?->city, $i->institution?->district]))],
                        ['Program', fn($i) => $i->institutionProgram?->program?->name ?? '—'],
                        ['Fee', fn($i) => $i->institutionProgram?->fee ? 'NPR ' . number_format($i->institutionProgram->fee) : '—'],
                        ['Duration', fn($i) => $i->institutionProgram?->duration ?? '—'],
                        ['Seats', fn($i) => $i->institutionProgram?->seats ?? '—'],
                        ['Min GPA', fn($i) => $i->institutionProgram?->min_gpa ?? '—'],
                        ['Min %', fn($i) => $i->institutionProgram?->min_percentage ? $i->institutionProgram->min_percentage . '%' : '—'],
                        ['Status', fn($i) => ucfirst($i->institution?->status ?? '—')],
                    ] as [$label, $fn])
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs font-bold text-gray-500">{{ $label }}</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $fn($item) }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                    <tr>
                        <td class="px-5 py-3 text-xs font-bold text-gray-500">Apply</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3">
                            @if($item->institution && $item->institutionProgram)
                            <a href="{{ route('student.applications.create', ['institution' => $item->institution->slug, 'program' => $item->institutionProgram->slug]) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#2c5aa0] text-white text-xs font-bold rounded-lg hover:bg-[#1a365d] transition no-underline">
                                Apply Now
                            </a>
                            @else
                            <span class="text-gray-400 text-xs">N/A</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        @elseif($items->count() == 1)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-5 py-4 text-sm text-yellow-700">
            <i class="fas fa-info-circle mr-2"></i> Add at least one more item to start comparing.
        </div>
        @endif

        @if($items->count() > 0 && $items->count() < 2)
        @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 flex items-center justify-between px-5 py-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <span class="text-[#2c5aa0] font-bold">{{ strtoupper(substr($item->institution?->name ?? 'I', 0, 1)) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-700">{{ $item->institution?->name }}</p>
                    @if($item->institutionProgram)
                    <p class="text-xs text-gray-400">{{ $item->institutionProgram?->program?->name }}</p>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('student.compare.destroy', $item) }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-semibold text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Remove</button>
            </form>
        </div>
        @endforeach
        @endif

        @if($items->count() === 0)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-balance-scale text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Compare list is empty</p>
            <p class="text-gray-400 text-sm mt-1">Add institutions or programs to compare them side by side</p>
            <a href="{{ route('website.institutions.index') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Browse Institutions</a>
        </div>
        @endif

    </div>
</section>

@endsection
