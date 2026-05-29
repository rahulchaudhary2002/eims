@extends('admin.layouts.app')
@section('title', 'Profile — ' . $studentProfile->student->name)
@section('page-title', 'Student Profile')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Student Profile"
        :subtitle="$studentProfile->student->name"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Student Profiles','route' => 'admin.student-profiles.index'],
            ['label'=>$studentProfile->student->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-profiles.edit', $studentProfile) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                Edit Profile
            </a>
            <a href="{{ route('admin.students.show', $studentProfile->student) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                View Student
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left column: Student + Guardian --}}
        <div class="space-y-5">

            {{-- Student Card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Student</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-base shrink-0">
                        {{ mb_strtoupper(mb_substr($studentProfile->student->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $studentProfile->student->name }}</p>
                        <p class="text-xs text-slate-500">{{ $studentProfile->student->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.students.show', $studentProfile->student) }}"
                    class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline pt-1">
                    View full student record →
                </a>
            </div>

            {{-- Guardian Card --}}
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Guardian</h3>
                @if($studentProfile->guardian_name || $studentProfile->guardian_phone)
                    <dl class="space-y-2 text-sm">
                        @if($studentProfile->guardian_name)
                        <div class="flex gap-2">
                            <dt class="text-slate-500 w-24 shrink-0">Name</dt>
                            <dd class="text-slate-800 font-medium">{{ $studentProfile->guardian_name }}</dd>
                        </div>
                        @endif
                        @if($studentProfile->guardian_phone)
                        <div class="flex gap-2">
                            <dt class="text-slate-500 w-24 shrink-0">Phone</dt>
                            <dd class="text-slate-800">{{ $studentProfile->guardian_phone }}</dd>
                        </div>
                        @endif
                    </dl>
                @else
                    <p class="text-sm text-slate-400 italic">No guardian information recorded.</p>
                @endif
            </div>

        </div>

        {{-- Middle + Right: Location + Preferences --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Location Card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Location</h3>
                @if($studentProfile->province || $studentProfile->district || $studentProfile->city || $studentProfile->address)
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500 text-xs mb-1">Province</dt>
                            <dd class="text-slate-800 font-medium">{{ $studentProfile->province ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 text-xs mb-1">District</dt>
                            <dd class="text-slate-800 font-medium">{{ $studentProfile->district ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 text-xs mb-1">City</dt>
                            <dd class="text-slate-800 font-medium">{{ $studentProfile->city ?? '—' }}</dd>
                        </div>
                        @if($studentProfile->address)
                        <div class="sm:col-span-3">
                            <dt class="text-slate-500 text-xs mb-1">Address</dt>
                            <dd class="text-slate-800">{{ $studentProfile->address }}</dd>
                        </div>
                        @endif
                        @if($studentProfile->preferred_location)
                        <div class="sm:col-span-3">
                            <dt class="text-slate-500 text-xs mb-1">Preferred Location</dt>
                            <dd class="text-slate-800 font-medium">{{ $studentProfile->preferred_location }}</dd>
                        </div>
                        @endif
                    </dl>
                @else
                    <p class="text-sm text-slate-400 italic">No location information recorded.</p>
                @endif
            </div>

            {{-- Budget Card --}}
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Budget</h3>
                @if($studentProfile->budget_min !== null || $studentProfile->budget_max !== null)
                    <div class="flex items-center gap-4 text-sm">
                        <div>
                            <p class="text-slate-500 text-xs mb-1">Minimum</p>
                            <p class="text-slate-800 font-semibold text-base">NPR {{ number_format($studentProfile->budget_min ?? 0) }}</p>
                        </div>
                        <div class="text-slate-300 text-lg">→</div>
                        <div>
                            <p class="text-slate-500 text-xs mb-1">Maximum</p>
                            <p class="text-slate-800 font-semibold text-base">
                                {{ $studentProfile->budget_max ? 'NPR ' . number_format($studentProfile->budget_max) : 'No limit' }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No budget preference specified.</p>
                @endif
            </div>

            {{-- Career Interests --}}
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Career Interests</h3>
                @php $ci = $studentProfile->career_interests ?? [] @endphp
                @if(count($ci))
                    <div class="flex flex-wrap gap-2">
                        @foreach($ci as $tag)
                            <span class="inline-flex items-center px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-sm font-medium">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No career interests recorded.</p>
                @endif
            </div>

            {{-- Preferred Faculties --}}
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Preferred Faculties</h3>
                @php $pf = $studentProfile->preferred_faculties ?? [] @endphp
                @if(count($pf))
                    <div class="flex flex-wrap gap-2">
                        @foreach($pf as $tag)
                            <span class="inline-flex items-center px-3 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-full text-sm font-medium">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">No preferred faculties recorded.</p>
                @endif
            </div>

        </div>
    </div>

    {{-- Meta + Danger Zone --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Timestamps --}}
        <div class="eims-card p-5 flex gap-8 text-sm">
            <div>
                <p class="text-slate-400 text-xs mb-1">Created</p>
                <p class="text-slate-700">{{ $studentProfile->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs mb-1">Last updated</p>
                <p class="text-slate-700">{{ $studentProfile->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="eims-card border border-red-100 p-5 flex items-center justify-between gap-4">
            <div>
                <p class="font-semibold text-red-600 text-sm">Delete Profile</p>
                <p class="text-xs text-slate-500 mt-0.5">Permanently remove this student's profile data.</p>
            </div>
            <form action="{{ route('admin.student-profiles.destroy', $studentProfile) }}"
                method="POST"
                onsubmit="return confirm('Permanently delete this student profile?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger text-sm">Delete</button>
            </form>
        </div>

    </div>

</div>
@endsection
