@extends('layouts.student')

@section('title', 'My Dashboard')

@section('content')

@php $profile = $student->profile; @endphp

{{-- Hero --}}
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5 mt-4">
            <div class="flex items-center gap-4">
                @if ($student->avatar)
                    <img src="{{ Storage::url($student->avatar) }}" alt="Avatar"
                         class="w-16 h-16 rounded-full object-cover border-4 border-white/30 flex-shrink-0">
                @else
                    <div class="w-16 h-16 rounded-full bg-white/20 border-4 border-white/30 flex items-center justify-center text-2xl font-bold flex-shrink-0">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-white/70 text-sm mb-0.5">Welcome back</p>
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight">{{ $student->name }}</h1>
                    @if ($profile?->city || $profile?->district)
                        <p class="text-white/65 text-sm mt-1">
                            <i class="fas fa-map-marker-alt text-[#4299e1] mr-1"></i>
                            {{ implode(', ', array_filter([$profile->city, $profile->district])) }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                     <a href="{{ route('website.applications.create') }}"
                   class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition no-underline text-sm">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </a>
                <a href="{{ route('student.profile.index') }}"
                   class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-5 py-2.5 rounded-xl transition no-underline text-sm">
                    <i class="fas fa-pen"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Stats Strip (overlapping) --}}
<div class="container max-w-7xl mx-auto px-4 -mt-8 relative z-10">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach ([
            ['label' => 'Total Applications', 'value' => $stats['applications'],             'icon' => 'fa-file-alt',      'accent' => 'border-blue-200 text-blue-600',    'bg' => 'bg-blue-50'],
            ['label' => 'Pending Review',      'value' => $stats['applications_pending'],     'icon' => 'fa-hourglass-half','accent' => 'border-amber-200 text-amber-600',  'bg' => 'bg-amber-50'],
            ['label' => 'Approved',            'value' => $stats['applications_approved'],    'icon' => 'fa-check-circle',  'accent' => 'border-emerald-200 text-emerald-600','bg' => 'bg-emerald-50'],
            ['label' => 'Scholarships',        'value' => $stats['scholarship_applications'], 'icon' => 'fa-award',         'accent' => 'border-purple-200 text-purple-600','bg' => 'bg-purple-50'],
            ['label' => 'Saved',               'value' => $stats['favorite_institutions'],    'icon' => 'fa-heart',         'accent' => 'border-rose-200 text-rose-600',   'bg' => 'bg-rose-50'],
            ['label' => 'Following',           'value' => $stats['followed_institutions'],    'icon' => 'fa-bell',          'accent' => 'border-teal-200 text-teal-600',   'bg' => 'bg-teal-50'],
        ] as $stat)
            <div class="bg-white rounded-xl border shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-4 flex flex-col items-center text-center border-gray-100">
                <span class="{{ $stat['bg'] }} {{ $stat['accent'] }} border w-10 h-10 rounded-xl flex items-center justify-center mb-3">
                    <i class="fas {{ $stat['icon'] }} text-sm"></i>
                </span>
                <p class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 mt-1 leading-tight">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</div>

{{-- Main Content --}}
<section class="bg-[#f7fafc] pt-10 pb-20">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_420px] gap-8 items-start">

            {{-- Left: Main Content --}}
            <div class="space-y-6">

                {{-- Recent Applications --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-file-alt text-[#4299e1]"></i> My Applications
                        </h2>
                                <a href="{{ route('website.applications.create') }}"
                           class="text-sm text-[#4299e1] hover:text-[#2c5aa0] font-semibold transition no-underline flex items-center gap-1">
                            <i class="fas fa-plus text-xs"></i> New Application
                        </a>
                    </div>

                    @if ($recentApplications->isEmpty())
                        <div class="text-center py-16 px-8">
                            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-400 flex items-center justify-center mx-auto mb-5">
                                <i class="fas fa-file-alt text-2xl"></i>
                            </div>
                            <h3 class="font-semibold text-gray-700 mb-2">No applications yet</h3>
                            <p class="text-sm text-gray-400 mb-7 max-w-xs mx-auto">Find an institution or program that matches your goals and submit your first application.</p>
                            <div class="flex justify-center gap-3 flex-wrap">
                                <a href="{{ route('website.institutions.index') }}"
                                   class="px-6 py-2.5 bg-[#2c5aa0] text-white text-sm font-semibold rounded-xl hover:bg-[#1a365d] transition no-underline">
                                    Browse Institutions
                                </a>
                                <a href="{{ route('website.programs.index') }}"
                                   class="px-6 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:border-[#4299e1] hover:text-[#2c5aa0] transition no-underline">
                                    Explore Programs
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($recentApplications as $application)
                                @php
                                    $sc = match(strtolower($application->status ?? '')) {
                                        'approved','accepted' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                        'rejected'            => ['bg' => 'bg-red-50',     'text' => 'text-red-700',     'border' => 'border-red-200',     'dot' => 'bg-red-500'],
                                        'pending','submitted' => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'border' => 'border-amber-200',   'dot' => 'bg-amber-500'],
                                        'withdrawn'           => ['bg' => 'bg-gray-50',    'text' => 'text-gray-500',    'border' => 'border-gray-200',    'dot' => 'bg-gray-400'],
                                        default               => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'border' => 'border-blue-200',    'dot' => 'bg-blue-500'],
                                    };
                                @endphp
                                <div class="rounded-xl border {{ $sc['border'] }} {{ $sc['bg'] }} p-4 flex flex-col gap-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="h-9 w-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-university text-[#2c5aa0] text-sm"></i>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white border {{ $sc['border'] }} {{ $sc['text'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                                            {{ ucfirst($application->status ?? 'Unknown') }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm leading-snug">
                                            {{ $application->institution?->name ?? 'Institution' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 truncate">
                                            {{ $application->applicable_label }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-auto">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        {{ $application->created_at?->format('M d, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Explore Section --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-compass text-[#4299e1]"></i> Explore
                        </h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-y divide-gray-100">
                        @foreach ([
                            ['route' => 'website.institutions.index',  'icon' => 'fa-university',    'label' => 'Institutions',  'count' => null],
                            ['route' => 'website.programs.index',      'icon' => 'fa-book-open',     'label' => 'Programs',      'count' => null],
                            ['route' => 'website.scholarships.index',  'icon' => 'fa-award',         'label' => 'Scholarships',  'count' => null],
                            ['route' => 'website.consultancies.index', 'icon' => 'fa-handshake',     'label' => 'Consultancies', 'count' => null],
                        ] as $exp)
                            <a href="{{ route($exp['route']) }}"
                               class="flex flex-col items-center gap-2 p-5 hover:bg-[#f7fafc] transition no-underline group">
                                <span class="w-10 h-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center group-hover:bg-[#4299e1] group-hover:text-white transition">
                                    <i class="fas {{ $exp['icon'] }} text-sm"></i>
                                </span>
                                <span class="text-sm font-semibold text-gray-700 group-hover:text-[#2c5aa0] transition">{{ $exp['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Recommendations --}}
                @if ($recommendations->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-star text-[#4299e1]"></i> Recommended For You
                            </h2>
                        </div>
                        <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($recommendations as $rec)
                                <div class="rounded-xl bg-[#f7fafc] border border-purple-100 p-4 flex items-start gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm truncate">
                                            {{ $rec->institution?->name ?? $rec->institutionProgram?->title ?? 'Recommendation' }}
                                        </p>
                                        @if ($rec->institution && $rec->institutionProgram)
                                            <p class="text-xs text-gray-400 truncate mt-0.5">{{ $rec->institutionProgram->title }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Sidebar --}}
            <aside class="lg:sticky lg:top-28 space-y-4">

                {{-- Profile + Quick Actions in same row --}}
                <div class="grid grid-cols-2 gap-4">

                    {{-- Profile Card --}}
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-br from-[#2c5aa0] to-[#4299e1] p-4 text-white text-center">
                            @if ($student->avatar)
                                <img src="{{ Storage::url($student->avatar) }}" alt="Avatar"
                                     class="w-12 h-12 rounded-full object-cover border-4 border-white/30 mx-auto mb-2">
                            @else
                                <div class="w-12 h-12 rounded-full bg-white/20 border-4 border-white/30 flex items-center justify-center text-lg font-bold mx-auto mb-2">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <h3 class="font-bold text-sm leading-tight truncate">{{ $student->name }}</h3>
                            <p class="text-white/65 text-[0.7rem] mt-0.5 truncate">{{ $student->email }}</p>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs font-semibold text-gray-600">Profile</span>
                                <span class="text-xs font-bold text-[#2c5aa0]">{{ $percent }}%</span>
                            </div>
                            <svg class="w-full h-2.5 mb-3 overflow-hidden rounded-full" viewBox="0 0 100 10" preserveAspectRatio="none" aria-hidden="true">
                                <rect width="100" height="10" rx="5" fill="#f3f4f6"></rect>
                                <rect width="{{ $percent }}" height="10" rx="5" fill="url(#student-dashboard-progress)"></rect>
                                <defs>
                                    <linearGradient id="student-dashboard-progress" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="0%" stop-color="#4299e1"></stop>
                                        <stop offset="100%" stop-color="#2c5aa0"></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <a href="{{ route('student.profile.index') }}"
                               class="w-full flex items-center justify-center gap-1.5 px-4 py-2.5 border-2 border-[#4299e1] text-[#2c5aa0] text-xs font-semibold rounded-xl hover:bg-[#4299e1]/10 transition no-underline">
                                <i class="fas fa-pen text-[10px]"></i>
                                {{ $percent < 100 ? 'Complete' : 'Edit Profile' }}
                            </a>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.06)] border border-gray-200 p-4">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Quick Access</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ([
                                ['route' => 'website.applications.create',          'icon' => 'fa-paper-plane',  'label' => 'Apply',        'primary' => true],
                                ['route' => 'website.inquiry.create',                'icon' => 'fa-question-circle','label' => 'Inquiry',    'primary' => false],
                                ['route' => 'website.compare.index',                 'icon' => 'fa-balance-scale','label' => 'Compare',      'primary' => false],
                                ['route' => 'student.scholarship-applications.index','icon' => 'fa-award',        'label' => 'Scholarship',  'primary' => false],
                            ] as $action)
                                <a href="{{ route($action['route']) }}"
                                   class="flex flex-col items-center justify-center gap-1.5 py-3 rounded-xl text-xs font-semibold transition no-underline
                                          {{ $action['primary'] ? 'bg-[#2c5aa0] text-white hover:bg-[#1a365d]' : 'border border-gray-200 text-gray-700 hover:border-[#4299e1] hover:text-[#2c5aa0] hover:bg-[#4299e1]/5' }}">
                                    <i class="fas {{ $action['icon'] }} text-sm {{ $action['primary'] ? '' : 'text-[#4299e1]' }}"></i>
                                    {{ $action['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Sign Out --}}
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 text-gray-500 text-sm font-medium rounded-xl hover:border-red-200 hover:text-red-500 hover:bg-red-50 transition">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </aside>

        </div>
    </div>
</section>
@endsection
