{{-- Usage: @include('website.partials.course-card', ['course' => $course]) --}}
@php $inst = $course->institution ?? null; @endphp
<div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-200 transition-all hover:-translate-y-1.5 hover:shadow-2xl flex flex-col">
    <div class="p-6 pb-4 border-b border-gray-100">
        <div class="flex items-start justify-between gap-3 mb-3">
            <div class="flex-1 min-w-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-600 rounded-full text-xs font-semibold mb-3">
                    <i class="fas fa-book-open text-[10px]"></i> Course
                </span>
                <h3 class="text-xl text-[#2c5aa0] font-bold leading-tight line-clamp-2">{{ $course->title }}</h3>
            </div>
        </div>
        @if ($inst)
            <div class="text-gray-500 flex items-center gap-1.5 text-sm">
                <i class="fas fa-university text-[#4299e1]"></i>
                <span class="truncate">{{ $inst->name }}</span>
            </div>
        @endif
    </div>

    <div class="p-5 flex-1 flex flex-col justify-between">
        @if ($course->description)
            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>
        @endif
        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-5">
            @if ($course->fee)
                <div class="flex items-center gap-1.5">
                    <i class="fas fa-money-bill-wave text-green-400"></i>
                    <span>NPR {{ number_format($course->fee) }}</span>
                </div>
            @endif
            @if ($course->duration_hours)
                <div class="flex items-center gap-1.5">
                    <i class="fas fa-clock text-blue-400"></i>
                    <span>{{ $course->duration_hours }} hours</span>
                </div>
            @endif
        </div>

        <div class="flex gap-2 justify-end">
            @auth('student')
                <a href="{{ route('website.applications.create', ['institution' => $inst?->slug, 'course' => $course->slug]) }}"
                   class="text-center px-4 py-2 text-sm font-semibold text-white bg-[#4299e1] rounded-xl hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition-all no-underline">
                    Apply
                </a>
            @else
                <a href="{{ route('student.login') }}"
                   class="text-center px-4 py-2 text-sm font-semibold text-white bg-[#4299e1] rounded-xl hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition-all no-underline">
                    Apply
                </a>
            @endauth
        </div>
    </div>
</div>
