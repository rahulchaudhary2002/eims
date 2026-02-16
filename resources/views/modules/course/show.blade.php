@extends('layouts.app')

@section('title', $course->name)

@section('content')
@php
$primaryProgram = $course->programs->first();
@endphp

{{-- HERO --}}
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-600 to-purple-600 p-8 mb-8 shadow-xl">
    <div class="absolute inset-0 bg-white/10 backdrop-blur-2xl"></div>

    <div class="relative flex items-center gap-6 text-white">
        <div class="w-24 h-24 rounded-2xl bg-white/90 flex items-center justify-center shadow-lg">
            <x-lucide-graduation-cap class="w-14 h-14 text-indigo-600" />
        </div>

        <div>
            <h1 class="text-4xl font-bold leading-tight">
                {{ $course->display_name }}
            </h1>

            <p class="mt-1 text-lg text-indigo-100">
                {{ $primaryProgram?->affiliation?->name ?? '—' }}
            </p>

            <p class="mt-2 inline-flex items-center gap-2 text-sm text-indigo-100">
                <span class="px-3 py-1 bg-white/20 rounded-full">
                    {{ $primaryProgram?->level?->name ?? '—' }}
                </span>
                <span class="px-3 py-1 bg-white/20 rounded-full">
                    {{ $primaryProgram?->duration ?? '—' }}
                </span>
            </p>
        </div>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-8">
    {{-- LEFT SIDEBAR --}}
    <aside class="lg:col-span-3">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 sticky top-28">
            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">
                Course Sections
            </h3>

            <ul class="space-y-1" id="course-nav">
                @if($course->programs->flatMap->institutions->count() > 0)
                <li>
                    <a href="#section-offered-by"
                        data-section-link
                        class="nav-link group flex items-center gap-3 px-4 py-2 rounded-xl
                          text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="w-1 h-1 rounded-full bg-gray-300 group-hover:bg-indigo-600"></span>
                        Offered By
                    </a>
                </li>
                @endif
                @foreach($course->descriptions->sortBy('order')->values() as $key => $section)
                <li>
                    <a href="#section-{{ $key + 1 }}"
                        data-section-link
                        class="nav-link group flex items-center gap-3 px-4 py-2 rounded-xl
                          text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <span class="w-1 h-1 rounded-full bg-gray-300 group-hover:bg-indigo-600"></span>
                        {{ $section->title }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </aside>


    {{-- MAIN CONTENT --}}
    <main class="lg:col-span-6 space-y-5">

        {{-- COURSE HEADER --}}
        <div class="bg-white">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $course->name }}
            </h1>

            <p class="text-gray-600 mt-2">
                {{ $primaryProgram?->level?->name ?? '—' }} ·
                {{ $primaryProgram?->duration ?? '—' }}
            </p>

            @if($course->description)
            <p class="mt-4 text-lg leading-relaxed">
                {{ $course->description }}
            </p>
            @endif
        </div>

        {{-- OFFERED BY SECTION --}}
        @if($course->programs->flatMap->institutions->count() > 0)
        <section id="section-offered-by">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                Institution Offering This Course
            </h2>

            <div class="space-y-4">
                @foreach($course->programs->flatMap->institutions->unique('id') as $institution)
                <div class="flex max-sm:flex-col sm:items-center sm:justify-between gap-4 p-4 rounded-xl border border-gray-200 hover:shadow-sm transition">

                    {{-- Left: Logo + Info --}}
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden border border-gray-200">
                            @if($institution->logo)
                            <img
                                src="{{ Storage::url($institution->logo) }}"
                                alt="{{ $institution->name }} Logo"
                                class="object-contain h-full w-full">
                            @else
                            <x-lucide-building class="w-8 h-8 text-gray-400" />
                            @endif
                        </div>

                        <div class="flex flex-col">
                            <a
                                href="{{ route('institution.show', [$institution->type, $institution->slug]) }}"
                                class="text-base font-semibold text-gray-800 hover:text-indigo-600 transition">
                                {{ $institution->name }}
                            </a>
                            <span class="text-sm text-gray-500">
                                {{ $institution->address }}
                            </span>
                        </div>
                    </div>

                    @php
                    $admission = $institution->admissions()->whereHas('programs', function($query) use ($course) {
                    $query->where('programs.id', $course->id);
                    })->first()
                    @endphp

                    @if($admission)
                    <a
                        href="{{ route('admission.apply', $admission) }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition whitespace-nowrap self-start sm:self-auto">
                        Apply Now
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- COURSE SECTIONS --}}
        @forelse($course->descriptions->sortBy('order')->values() as $key => $section)
        <section id="section-{{ $key + 1 }}"
            class="bg-white scroll-mt-28 {{ $key !== 0 ? 'border border-gray-200 rounded-xl px-6' : '' }}">
            <div class="prose max-w-none prose-blue no-tailwind">
                {!! $section->content !!}
            </div>
        </section>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-6 text-center text-gray-500">
            No content available for this course.
        </div>
        @endforelse

    </main>

    {{-- RIGHT SIDEBAR (RELATED PROGRAMS) --}}
    <aside class="lg:col-span-3 space-y-6">

        <h3 class="text-lg font-semibold text-gray-800">
            Related Programs
        </h3>

        {{-- Uncomment when you have related courses
        @foreach($relatedCourses as $related)
        <a href="{{ route('course.show', $related) }}"
        class="block bg-white rounded-xl border border-gray-200 p-4 hover:shadow transition">
        <h4 class="font-semibold text-gray-800">
            {{ $related->name }}
        </h4>
        <p class="text-sm text-gray-500 mt-1">
            {{ $related->affiliation?->name ?? '—' }}
        </p>
        <p class="text-sm text-gray-500 mt-1">
            {{ $related->duration ?? '' }}
        </p>
        </a>
        @endforeach
        --}}

    </aside>

</div>

@endsection

@section('page-specific-script')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const links = document.querySelectorAll('[data-section-link]');
        const sections = [...links].map(link =>
            document.querySelector(link.getAttribute('href'))
        );

        const offset = 120; // sticky header height

        function activateLink() {
            let current = null; // default to none
            const scrollPosition = window.scrollY + offset + 1; // +1 to avoid rounding issues
            const pageHeight = document.documentElement.scrollHeight;
            const windowHeight = window.innerHeight;

            // Find the section closest to top but only if user scrolled past it
            sections.forEach(section => {
                if (section.offsetTop <= scrollPosition) {
                    current = section;
                }
            });

            // If scrolled to bottom, activate last section
            if (window.scrollY + windowHeight >= pageHeight - 5) {
                current = sections[sections.length - 1];
            }

            // Update sidebar links
            links.forEach(link => {
                link.classList.toggle('active', current && link.getAttribute('href') === `#${current.id}`);
            });

            // Update URL fragment
            if (current) {
                history.replaceState(null, '', `#${current.id}`);
            }
        }

        // Smooth scroll when clicking sidebar links
        links.forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (!target) return;
                window.scrollTo({
                    top: target.offsetTop - offset,
                    behavior: 'smooth'
                });
            });
        });

        // Scroll event triggers activation
        window.addEventListener('scroll', activateLink);
    });
</script>


@endsection
