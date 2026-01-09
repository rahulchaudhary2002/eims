@extends('vendor.layouts.app')
@section('title', 'Application Details')

@section('content')
<div class="bg-white rounded-lg shadow-lg border text-base border-gray-200">
    {{-- Header --}}
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📝 Application Details</h1>
            <p class="text-gray-600 mt-1">View applicant information</p>
        </div>
        <a href="{{ route('vendor.admission.application.index', $admission) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" /> Back
        </a>
    </div>

    <div class="px-6 pb-6 space-y-6">
        {{-- Basic Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 font-medium">Full Name</p>
                <p class="text-gray-800">{{ $application->full_name }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Email</p>
                <p class="text-gray-800">{{ $application->email }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Phone</p>
                <p class="text-gray-800">{{ $application->phone }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Status</p>
                <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-800 capitalize">{{ $application->status }}</span>
            </div>
            @if($admission->isForCourse())
            <div>
                <p class="text-gray-500 font-medium">Course</p>
                <p class="text-gray-800">{{ optional($application->course)->name }}</p>
            </div>
            @elseif($admission->isForGrade())
            <div>
                <p class="text-gray-500 font-medium">Grade</p>
                <p class="text-gray-800">{{ $application->grade }}</p>
            </div>
            @endif
            <div class="md:col-span-2">
                <p class="text-gray-500 font-medium">Notes</p>
                <div class="text-gray-800 mt-1 no-tailwind">
                    {!! nl2br(e($application->notes)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection