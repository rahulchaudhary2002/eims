@extends('admin.layouts.app')
@section('title', 'Course Details')
@section('page-title', 'Course Details')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Course Details"
        :subtitle="$course->name"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Courses',   'route' => 'admin.course.index'],
            ['label' => 'Details'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.course.edit', $course) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit Course
            </a>
            <a href="{{ route('admin.course.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Course Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left Column: Core Details --}}
        <div class="space-y-5">
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <x-lucide-book-open class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Course Details</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Course Name</label>
                        <p class="text-slate-900 font-medium">{{ $course->name }}</p>
                    </div>

                    @if($course->code)
                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Course Code</label>
                        <code class="text-sm bg-slate-50 px-3 py-1.5 rounded-lg text-slate-700 font-mono border border-slate-200 inline-block">{{ $course->code }}</code>
                    </div>
                    @endif

                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Status</label>
                        <div>
                            <x-admin.status-badge :status="$course->is_active ? 'active' : 'inactive'" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Course ID</label>
                        <code class="text-sm bg-slate-50 px-3 py-1.5 rounded-lg text-slate-700 font-mono border border-slate-200 inline-block">#{{ $course->id }}</code>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Program Metadata --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-lucide-layers class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Program Information</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Programs</label>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @forelse($course->programs as $program)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $program->name }}
                            </span>
                            @empty
                            <span class="text-slate-400 text-sm">-</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Level</label>
                        <p class="text-slate-800 font-medium">
                            {{ $course->programs->pluck('level.name')->filter()->unique()->join(', ') ?: '-' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Affiliation</label>
                        <p class="text-slate-800 font-medium">
                            {{ $course->programs->pluck('affiliation.name')->filter()->unique()->join(', ') ?: '-' }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <label class="form-label text-xs uppercase tracking-wider">Duration</label>
                        <p class="text-slate-800 font-medium">
                            {{ $course->programs->pluck('duration')->filter()->unique()->join(', ') ?: '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- About this Course --}}
            @if($course->description)
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-emerald-50 rounded-lg">
                        <x-lucide-file-text class="w-5 h-5 text-emerald-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">About this Course</h3>
                </div>
                <p class="text-slate-700 leading-relaxed">{{ $course->description }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Course Content Sections --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="p-2 bg-violet-50 rounded-lg">
                <x-lucide-layout-list class="w-5 h-5 text-violet-600" />
            </div>
            <h3 class="text-base font-semibold text-slate-900">Course Content</h3>
        </div>

        {{-- Jump to Section --}}
        @if($course->descriptions->count())
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider mb-3">Jump to Section</p>
            <ul class="space-y-1.5">
                @foreach($course->descriptions as $key => $section)
                <li>
                    <a href="#section-{{ $loop->iteration }}"
                        class="flex items-center gap-2 text-sm text-primary-600 hover:text-primary-800 hover:underline transition-colors">
                        <span class="w-5 h-5 rounded-full bg-primary-100 text-primary-700 text-xs font-semibold flex items-center justify-center shrink-0">{{ $loop->iteration }}</span>
                        {{ $section->title }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Sections --}}
        @forelse($course->descriptions as $key => $section)
        @php $sectionId = 'section-' . $loop->iteration; @endphp

        <div id="{{ $sectionId }}" class="mb-8 scroll-mt-28">
            <a href="#{{ $sectionId }}" class="group flex items-center gap-3 mb-4">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary-100 text-primary-700 font-semibold text-sm group-hover:bg-primary-200 transition shrink-0">
                    {{ $loop->iteration }}
                </span>
                <h3 class="text-lg font-semibold text-slate-800 group-hover:text-primary-600 transition">
                    {{ $section->title }}
                </h3>
            </a>

            <div class="prose max-w-none prose-blue no-tailwind pl-11">
                {!! $section->content !!}
            </div>

            @if(!$loop->last)
            <hr class="mt-8 border-slate-200">
            @endif
        </div>

        @empty
        <div class="py-8 text-center text-slate-400 text-sm">
            No course sections added yet.
        </div>
        @endforelse
    </div>

</div>
@endsection
