@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<main class="flex-1 py-10 px-4 mt-[80px]">
    <div class="container mx-auto max-w-6xl space-y-6">

        {{-- Welcome Banner --}}
        <div class="bg-gradient-to-r from-[#2c5aa0] to-[#4299e1] rounded-2xl p-6 text-white flex items-center gap-5">
            <div>
                @if($student->avatar)
                    <img src="{{ Storage::url($student->avatar) }}" alt="Avatar"
                         class="w-16 h-16 rounded-full object-cover border-2 border-white/40">
                @else
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold">Welcome back, {{ $student->name }}!</h1>
                <p class="opacity-80 text-sm mt-1">
                    @if($student->profile?->city || $student->profile?->district)
                        {{ implode(', ', array_filter([$student->profile->city, $student->profile->district])) }}
                    @else
                        Complete your profile to get personalised recommendations.
                    @endif
                </p>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="hidden sm:inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                </svg>
                Edit Profile
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $statCards = [
                    ['label' => 'Applications',      'value' => $stats['applications'],             'bg' => 'bg-blue-50',   'text' => 'text-blue-700'],
                    ['label' => 'Pending',            'value' => $stats['applications_pending'],     'bg' => 'bg-amber-50',  'text' => 'text-amber-700'],
                    ['label' => 'Approved',           'value' => $stats['applications_approved'],    'bg' => 'bg-emerald-50','text' => 'text-emerald-700'],
                    ['label' => 'Scholarships',       'value' => $stats['scholarship_applications'], 'bg' => 'bg-purple-50', 'text' => 'text-purple-700'],
                    ['label' => 'Saved',              'value' => $stats['favorite_institutions'],    'bg' => 'bg-rose-50',   'text' => 'text-rose-700'],
                    ['label' => 'Following',          'value' => $stats['followed_institutions'],    'bg' => 'bg-teal-50',   'text' => 'text-teal-700'],
                ];
            @endphp
            @foreach($statCards as $card)
                <div class="bg-white rounded-xl shadow p-4 text-center">
                    <div class="text-2xl font-bold {{ $card['bg'] }} {{ $card['text'] }} rounded-lg py-1">
                        {{ $card['value'] }}
                    </div>
                    <p class="text-xs text-gray-500 mt-2 font-medium">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Recent Applications --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Recent Applications</h2>
                </div>

                @if($recentApplications->isEmpty())
                    <div class="text-center py-10 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p class="text-sm">No applications yet.</p>
                        <a href="{{ route('institution.index') }}" class="mt-3 inline-block text-[#4299e1] text-sm hover:underline">
                            Browse institutions &rarr;
                        </a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($recentApplications as $application)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate">
                                        {{ $application->institution?->name ?? 'Institution' }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $application->program?->name ?? 'Program' }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @php
                                        $statusColor = match(strtolower($application->status ?? '')) {
                                            'approved', 'accepted' => 'bg-green-100 text-green-700',
                                            'rejected'             => 'bg-red-100 text-red-700',
                                            'pending'              => 'bg-amber-100 text-amber-700',
                                            default                => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($application->status ?? 'Unknown') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Sidebar: Profile completeness + Recommendations + Quick Links --}}
            <div class="flex flex-col gap-4">

                {{-- Profile Completeness --}}
                @php
                    $profile = $student->profile;
                    $fields  = [
                        $student->name, $student->email, $student->phone,
                        $student->date_of_birth, $student->gender, $student->avatar,
                        $profile?->address, $profile?->province,
                        $profile?->career_interests, $profile?->preferred_faculties,
                    ];
                    $filled  = count(array_filter($fields, fn($v) => !empty($v)));
                    $total   = count($fields);
                    $percent = (int) round(($filled / $total) * 100);
                @endphp
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Profile Completeness</h2>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full bg-[#4299e1] transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-sm font-bold text-[#2c5aa0]">{{ $percent }}%</span>
                    </div>
                    @if($percent < 100)
                        <a href="{{ route('profile.edit') }}" class="text-xs text-[#4299e1] hover:underline">
                            Complete your profile &rarr;
                        </a>
                    @else
                        <p class="text-xs text-green-600 font-medium">Profile complete!</p>
                    @endif
                </div>

                {{-- Recommendations --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Recommended For You</h2>
                    @if($recommendations->isEmpty())
                        <p class="text-xs text-gray-400">No recommendations yet. Complete your profile to get personalised suggestions.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($recommendations as $rec)
                                <div class="text-xs">
                                    <p class="font-medium text-gray-700 truncate">
                                        {{ $rec->institution?->name ?? $rec->program?->name ?? 'Recommendation' }}
                                    </p>
                                    @if($rec->institution && $rec->program)
                                        <p class="text-gray-400 truncate">{{ $rec->program->name }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Quick Links --}}
                <div class="bg-white rounded-2xl shadow p-5">
                    <h2 class="text-sm font-semibold text-gray-700 mb-3">Quick Links</h2>
                    <ul class="space-y-2 text-sm">
                        <li>
                            <a href="{{ route('institution.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#4299e1]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                Browse Institutions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('program.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#4299e1]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                Explore Programs
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('forum.question.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#4299e1]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                                Student Forum
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-gray-600 hover:text-[#4299e1]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                My Profile
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- Sign out --}}
        <div class="text-right">
            <form method="POST" action="{{ route('student.logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition">Sign out</button>
            </form>
        </div>

    </div>
</main>
@endsection
