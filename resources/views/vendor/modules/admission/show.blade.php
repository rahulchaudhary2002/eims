@extends('vendor.layouts.app')
@section('title', $admission->title)

@section('content')
<div class="bg-white rounded-lg shadow-lg border text-base border-gray-200">
    {{-- Header --}}
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🎓 Admission Details</h1>
            <p class="text-gray-600 mt-1">View admission information</p>
        </div>
        <a href="{{ route('vendor.admission.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" /> Back
        </a>
    </div>

    <div class="px-6 pb-6 space-y-6">
        {{-- Basic Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 font-medium">Title</p>
                <p class="text-gray-800">{{ $admission->title }}</p>
            </div>

            <div>
                <p class="text-gray-500 font-medium">Admission Type</p>
                <p class="text-gray-800 capitalize">{{ $admission->admission_type }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 font-medium">Courses / Grades</p>

                @if($admission->admission_type === 'course')
                <ul class="list-disc list-inside text-gray-800 mt-1">
                    @foreach($admission->courses as $course)
                    <li>{{ $course->name }}</li>
                    @endforeach
                </ul>
                @elseif($admission->admission_type === 'grade')
                <div x-data="{ grades: @json($admission->grades) }">
                    <template x-for="(grade, index) in grades" :key="index">
                        <p class="text-gray-800" x-text="grade"></p>
                    </template>
                </div>
                @endif
            </div>

            <div>
                <p class="text-gray-500 font-medium">Start Date</p>
                <p class="text-gray-800">
                    {{ \Carbon\Carbon::parse($admission->start_date)->format('Y-m-d') }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 font-medium">End Date</p>
                <p class="text-gray-800">
                    {{ \Carbon\Carbon::parse($admission->end_date)->format('Y-m-d') }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 font-medium">Description</p>
                <div class="text-gray-800 mt-1 no-tailwind">
                    {!! $admission->description !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection