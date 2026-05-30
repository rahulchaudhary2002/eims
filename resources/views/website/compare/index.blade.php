@extends('website.layouts.app')

@section('meta-title', 'Compare Institutions & Programs - ' . config('app.name'))
@section('meta-description', 'Compare institutions and programs side by side to find your best fit.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Compare'],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-balance-scale text-[#4299e1]"></i>
                Side-by-Side Comparison
            </span>
            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Compare</h1>
            <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                Evaluate institutions and programs side by side - compare fees, seats, ratings, and more to make the best choice for your academic future.
            </p>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if ($items->isEmpty() && $institutions->isEmpty() && $programs->isEmpty())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 py-20 text-center">
                <i class="fas fa-balance-scale text-gray-200 text-6xl mb-5"></i>
                <h2 class="text-[1.5rem] font-bold text-gray-700 mb-3">Nothing to Compare Yet</h2>
                <p class="text-gray-500 text-[0.95rem] mb-8 max-w-sm mx-auto">Browse institutions and programs and click "Add to Compare" to compare them side by side.</p>
                <div class="flex justify-center gap-3 flex-wrap">
                    <a href="{{ route('website.institutions.index') }}"
                       class="px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white font-semibold rounded-xl hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                        <i class="fas fa-university mr-2"></i> Browse Institutions
                    </a>
                    <a href="{{ route('website.programs.index') }}"
                       class="px-6 py-3 border-2 border-[#4299e1] text-[#2c5aa0] font-semibold rounded-xl hover:bg-[#4299e1]/10 transition no-underline">
                        <i class="fas fa-book-open mr-2"></i> Browse Programs
                    </a>
                </div>
            </div>
        @endif

        {{-- ── STUDENT: Paired institution+program table ── --}}
        @if ($items->isNotEmpty())
        @php
            $yesIcon = '<span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-green-100 text-green-600"><i class="fas fa-check text-xs"></i></span>';
            $noIcon  = '<span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 text-gray-400"><i class="fas fa-times text-xs"></i></span>';
        @endphp
        <div class="overflow-x-auto mb-10">
            <table class="w-full bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-4 text-left text-xs font-bold text-gray-500 w-44">Feature</th>
                        @foreach($items as $item)
                        <th class="px-5 py-4 text-left min-w-[220px]">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $item->institution?->name }}</p>
                                    @if($item->institutionProgram)
                                    <p class="text-xs text-[#4299e1] font-medium mt-0.5">{{ $item->institutionProgram?->program?->name }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($item->institution?->type ?? '') }}</p>
                                </div>
                                <form method="POST" action="{{ route('website.compare.destroy-item', $item->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-400 transition shrink-0 mt-0.5">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    {{-- General --}}
                    <tr class="bg-[#f7fafc]">
                        <td colspan="{{ $items->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">General</td>
                    </tr>
                    @foreach([
                        ['Location',    fn($i) => implode(', ', array_filter([$i->institution?->city, $i->institution?->district, $i->institution?->province]))],
                        ['Established', fn($i) => $i->institution?->established_year ?? '-'],
                        ['Status',      fn($i) => ucfirst($i->institution?->status ?? '-')],
                        ['Verified',    fn($i) => $i->institution?->is_verified ? '✓ Verified' : 'Not Verified'],
                    ] as [$label, $fn])
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs font-semibold text-gray-500">{{ $label }}</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $fn($item) }}</td>
                        @endforeach
                    </tr>
                    @endforeach

                    {{-- Program & Fees --}}
                    <tr class="bg-[#f7fafc]">
                        <td colspan="{{ $items->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Program & Fees</td>
                    </tr>
                    @foreach([
                        ['Program',        fn($i) => $i->institutionProgram?->program?->name ?? '-'],
                        ['Total Fee',      fn($i) => $i->institutionProgram?->total_fee      ? 'NPR ' . number_format($i->institutionProgram->total_fee)      : '-'],
                        ['Admission Fee',  fn($i) => $i->institutionProgram?->admission_fee  ? 'NPR ' . number_format($i->institutionProgram->admission_fee)  : '-'],
                        ['Semester Fee',   fn($i) => $i->institutionProgram?->semester_fee   ? 'NPR ' . number_format($i->institutionProgram->semester_fee)   : '-'],
                        ['Duration',       fn($i) => $i->institutionProgram?->duration_months ? $i->institutionProgram->duration_months . ' months'           : '-'],
                        ['Total Seats',    fn($i) => $i->institutionProgram?->total_seats     ?? '-'],
                        ['Avail. Seats',   fn($i) => $i->institutionProgram?->available_seats ?? '-'],
                        ['Min GPA',        fn($i) => $i->institutionProgram?->minimum_gpa        ?? '-'],
                        ['Min %',          fn($i) => $i->institutionProgram?->minimum_percentage ? $i->institutionProgram->minimum_percentage . '%' : '-'],
                        ['Deadline',       fn($i) => $i->institutionProgram?->admission_end_date ? 'Until ' . \Carbon\Carbon::parse($i->institutionProgram->admission_end_date)->format('M d, Y') : '-'],
                    ] as [$label, $fn])
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs font-semibold text-gray-500">{{ $label }}</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3 text-sm text-gray-700">{{ $fn($item) }}</td>
                        @endforeach
                    </tr>
                    @endforeach

                    {{-- Infrastructure --}}
                    <tr class="bg-[#f7fafc]">
                        <td colspan="{{ $items->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Infrastructure</td>
                    </tr>
                    @foreach([
                        ['Hostel',          fn($i) => $i->institution?->profile?->has_hostel],
                        ['Library',         fn($i) => $i->institution?->profile?->has_library],
                        ['Computer Lab',    fn($i) => $i->institution?->profile?->has_lab],
                        ['Cafeteria',       fn($i) => $i->institution?->profile?->has_cafeteria],
                        ['Sports Facility', fn($i) => $i->institution?->profile?->has_sports],
                        ['Transportation',  fn($i) => $i->institution?->profile?->has_transportation],
                        ['Scholarship',     fn($i) => $i->institution?->profile?->has_scholarship],
                    ] as [$label, $fn])
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs font-semibold text-gray-500">{{ $label }}</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3">{!! $fn($item) ? $yesIcon : $noIcon !!}</td>
                        @endforeach
                    </tr>
                    @endforeach
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 text-xs font-semibold text-gray-500">Facilities</td>
                        @foreach($items as $item)
                        <td class="px-5 py-3">
                            @php $facilities = $item->institution?->profile?->facilities ?? []; @endphp
                            @if(count($facilities))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($facilities as $f)
                                    <span class="text-[11px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-medium">{{ $f }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>

                    {{-- Apply --}}
                    <tr class="bg-gray-50">
                        <td class="px-5 py-4 text-xs font-bold text-gray-500">Apply</td>
                        @foreach($items as $item)
                        <td class="px-5 py-4">
                            @if($item->institution && $item->institutionProgram)
                            <a href="{{ route('website.applications.create', ['institution' => $item->institution->slug, 'program' => $item->institutionProgram->slug]) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-xs font-bold rounded-lg hover:opacity-90 transition no-underline">
                                <i class="fas fa-paper-plane text-[10px]"></i> Apply Now
                            </a>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>

                </tbody>
            </table>
        </div>
        @endif

        {{-- Institutions Comparison --}}
        @if ($institutions->isNotEmpty())
            <div class="mb-8">
                <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Institutions</h2>
                <p class="text-gray-600 text-[0.95rem] mt-5">Comparing {{ $institutions->count() }} institution{{ $institutions->count() !== 1 ? 's' : '' }}.</p>
            </div>
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-x-auto mb-10">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#f7fafc] border-b border-gray-200">
                            <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wide w-40">Attribute</th>
                            @foreach ($institutions as $inst)
                                <th class="px-5 py-4 text-center min-w-52">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="h-14 w-14 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-center overflow-hidden">
                                            @if ($inst->logo)
                                                <img src="{{ Storage::url($inst->logo) }}" alt="{{ $inst->name }}" class="w-12 h-12 object-contain">
                                            @else
                                                <i class="fas fa-university text-[#2c5aa0] text-xl"></i>
                                            @endif
                                        </div>
                                        <span class="font-bold text-gray-900">{{ $inst->name }}</span>
                                        <form method="POST" action="{{ route('website.compare.destroy', ['type' => 'institution', 'slug' => $inst->slug]) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                                                <i class="fas fa-times mr-1"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        {{-- General --}}
                        <tr class="bg-[#f7fafc]">
                            <td colspan="{{ $institutions->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">General</td>
                        </tr>
                        @foreach ([
                            ['key' => 'type', 'label' => 'Type', 'format' => 'type'],
                            ['key' => 'city', 'label' => 'Location', 'format' => 'text'],
                            ['key' => 'established_year', 'label' => 'Est. Year', 'format' => 'text'],
                            ['key' => 'is_verified', 'label' => 'Verified', 'format' => 'bool'],
                            ['key' => 'programs_count', 'label' => 'Programs', 'format' => 'number'],
                            ['key' => 'reviews_avg_rating', 'label' => 'Avg Rating', 'format' => 'rating'],
                            ['key' => 'reviews_count', 'label' => 'Reviews', 'format' => 'number'],
                        ] as $attr)
                            <tr class="hover:bg-[#f7fafc] transition">
                                <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">{{ $attr['label'] }}</td>
                                @foreach ($institutions as $inst)
                                    <td class="px-5 py-4 text-center text-gray-900 font-medium">
                                        @php $val = $inst->{$attr['key']}; @endphp
                                        @if ($attr['format'] === 'bool')
                                            @if ($val)
                                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-green-100 text-green-600"><i class="fas fa-check text-xs"></i></span>
                                            @else
                                                <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 text-gray-400"><i class="fas fa-times text-xs"></i></span>
                                            @endif
                                        @elseif ($attr['format'] === 'type')
                                            {{ \App\Models\Institution::TYPES[$val] ?? $val ?? '-' }}
                                        @elseif ($attr['format'] === 'rating')
                                            @if ($val)
                                                <span class="inline-flex items-center gap-1 text-yellow-500 font-bold">
                                                    <i class="fas fa-star text-xs"></i> {{ number_format($val, 1) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        @elseif ($attr['format'] === 'number')
                                            {{ $val ?? '0' }}
                                        @else
                                            {{ $val ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- Infrastructure --}}
                        <tr class="bg-[#f7fafc]">
                            <td colspan="{{ $institutions->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Infrastructure</td>
                        </tr>
                        @foreach ([
                            ['field' => 'has_hostel',        'label' => 'Hostel'],
                            ['field' => 'has_library',       'label' => 'Library'],
                            ['field' => 'has_lab',           'label' => 'Computer Lab'],
                            ['field' => 'has_cafeteria',     'label' => 'Cafeteria'],
                            ['field' => 'has_sports',        'label' => 'Sports Facility'],
                            ['field' => 'has_transportation','label' => 'Transportation'],
                            ['field' => 'has_scholarship',   'label' => 'Scholarship'],
                        ] as $infra)
                            <tr class="hover:bg-[#f7fafc] transition">
                                <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">{{ $infra['label'] }}</td>
                                @foreach ($institutions as $inst)
                                    <td class="px-5 py-4 text-center">
                                        @if ($inst->profile?->{$infra['field']})
                                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-green-100 text-green-600"><i class="fas fa-check text-xs"></i></span>
                                        @else
                                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 text-gray-400"><i class="fas fa-times text-xs"></i></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="hover:bg-[#f7fafc] transition">
                            <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Facilities</td>
                            @foreach ($institutions as $inst)
                                <td class="px-5 py-4 text-center">
                                    @php $facilities = $inst->profile?->facilities ?? []; @endphp
                                    @if(count($facilities))
                                        <div class="flex flex-wrap justify-center gap-1">
                                            @foreach($facilities as $f)
                                            <span class="text-[11px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-medium">{{ $f }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <tr class="bg-[#f7fafc]">
                            <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Actions</td>
                            @foreach ($institutions as $inst)
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('website.institutions.show', $inst->slug) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-xs font-bold rounded-lg hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Programs Comparison --}}
        @if ($programs->isNotEmpty())
            <div class="mb-8">
                <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Programs</h2>
                <p class="text-gray-600 text-[0.95rem] mt-5">Comparing {{ $programs->count() }} program{{ $programs->count() !== 1 ? 's' : '' }}.</p>
            </div>
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#f7fafc] border-b border-gray-200">
                            <th class="text-left px-5 py-4 text-xs font-bold text-gray-500 uppercase tracking-wide w-40">Attribute</th>
                            @foreach ($programs as $prog)
                                <th class="px-5 py-4 text-center min-w-52">
                                    <div class="flex flex-col items-center gap-2">
                                        <div class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center">
                                            <i class="fas fa-book-open"></i>
                                        </div>
                                        <span class="font-bold text-gray-900">{{ $prog->display_name }}</span>
                                        <span class="text-xs text-gray-500">{{ $prog->institution?->name }}</span>
                                        <form method="POST" action="{{ route('website.compare.destroy', ['type' => 'program', 'slug' => $prog->slug]) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold transition">
                                                <i class="fas fa-times mr-1"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        {{-- Fees & Availability --}}
                        <tr class="bg-[#f7fafc]">
                            <td colspan="{{ $programs->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Fees & Availability</td>
                        </tr>
                        @foreach ([
                            ['key' => 'status',        'label' => 'Status',           'format' => 'status'],
                            ['key' => 'total_fee',     'label' => 'Total Fee',         'format' => 'currency'],
                            ['key' => 'admission_fee', 'label' => 'Admission Fee',     'format' => 'currency'],
                            ['key' => 'semester_fee',  'label' => 'Semester Fee',      'format' => 'currency'],
                            ['key' => 'duration_months','label'=> 'Duration',          'format' => 'duration'],
                            ['key' => 'total_seats',   'label' => 'Total Seats',       'format' => 'text'],
                            ['key' => 'available_seats','label'=> 'Available Seats',   'format' => 'text'],
                            ['key' => 'admission_end_date','label'=> 'Deadline',       'format' => 'date'],
                        ] as $attr)
                            <tr class="hover:bg-[#f7fafc] transition">
                                <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">{{ $attr['label'] }}</td>
                                @foreach ($programs as $prog)
                                    <td class="px-5 py-4 text-center text-gray-900 font-medium">
                                        @php $val = $prog->{$attr['key']}; @endphp
                                        @if ($attr['format'] === 'currency')
                                            {{ $val ? 'NPR ' . number_format($val) : '-' }}
                                        @elseif ($attr['format'] === 'duration')
                                            {{ $val ? $val . ' months' : '-' }}
                                        @elseif ($attr['format'] === 'date')
                                            {{ $val ? 'Until ' . \Carbon\Carbon::parse($val)->format('M d, Y') : '-' }}
                                        @elseif ($attr['format'] === 'status')
                                            @if ($val === 'open')
                                                <span class="inline-flex items-center gap-1 text-green-600 font-semibold text-xs bg-green-50 px-2 py-1 rounded-full">
                                                    <i class="fas fa-circle text-[8px]"></i> Open
                                                </span>
                                            @elseif ($val)
                                                <span class="inline-flex items-center gap-1 text-gray-500 text-xs bg-gray-50 px-2 py-1 rounded-full capitalize">{{ $val }}</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        @else
                                            {{ $val ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- Requirements --}}
                        <tr class="bg-[#f7fafc]">
                            <td colspan="{{ $programs->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Requirements</td>
                        </tr>
                        @foreach ([
                            ['key' => 'minimum_gpa',        'label' => 'Min GPA', 'format' => 'text'],
                            ['key' => 'minimum_percentage', 'label' => 'Min %',   'format' => 'percent'],
                        ] as $attr)
                            <tr class="hover:bg-[#f7fafc] transition">
                                <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">{{ $attr['label'] }}</td>
                                @foreach ($programs as $prog)
                                    <td class="px-5 py-4 text-center text-gray-900 font-medium">
                                        @php $val = $prog->{$attr['key']}; @endphp
                                        {{ $attr['format'] === 'percent' ? ($val ? $val . '%' : '-') : ($val ?? '-') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        {{-- Infrastructure (from institution) --}}
                        <tr class="bg-[#f7fafc]">
                            <td colspan="{{ $programs->count() + 1 }}" class="px-5 py-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Infrastructure</td>
                        </tr>
                        @foreach ([
                            ['field' => 'has_hostel',         'label' => 'Hostel'],
                            ['field' => 'has_library',        'label' => 'Library'],
                            ['field' => 'has_lab',            'label' => 'Computer Lab'],
                            ['field' => 'has_cafeteria',      'label' => 'Cafeteria'],
                            ['field' => 'has_sports',         'label' => 'Sports Facility'],
                            ['field' => 'has_transportation', 'label' => 'Transportation'],
                            ['field' => 'has_scholarship',    'label' => 'Scholarship'],
                        ] as $infra)
                            <tr class="hover:bg-[#f7fafc] transition">
                                <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">{{ $infra['label'] }}</td>
                                @foreach ($programs as $prog)
                                    <td class="px-5 py-4 text-center">
                                        @if ($prog->institution?->profile?->{$infra['field']})
                                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-green-100 text-green-600"><i class="fas fa-check text-xs"></i></span>
                                        @else
                                            <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-gray-100 text-gray-400"><i class="fas fa-times text-xs"></i></span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr class="hover:bg-[#f7fafc] transition">
                            <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Facilities</td>
                            @foreach ($programs as $prog)
                                <td class="px-5 py-4 text-center">
                                    @php $facilities = $prog->institution?->profile?->facilities ?? []; @endphp
                                    @if(count($facilities))
                                        <div class="flex flex-wrap justify-center gap-1">
                                            @foreach($facilities as $f)
                                            <span class="text-[11px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md font-medium">{{ $f }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <tr class="bg-[#f7fafc]">
                            <td class="px-5 py-4 text-gray-500 font-semibold text-xs uppercase tracking-wide">Actions</td>
                            @foreach ($programs as $prog)
                                <td class="px-5 py-4 text-center">
                                    @if ($prog->status === 'open')
                                        <a href="{{ route('website.applications.create', ['institution' => $prog->institution?->slug, 'program' => $prog->slug]) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-xs font-bold rounded-lg hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                                            <i class="fas fa-paper-plane"></i> Apply
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Not open</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
