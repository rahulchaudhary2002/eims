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
        {{-- Approve/Reject Buttons --}}
        @if($application->status === 'pending')
        <div class="flex space-x-3 mb-4">
            <form action="{{ route('vendor.admission.application.update-status', [$admission, $application]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded flex items-center text-sm font-semibold">
                    <x-lucide-check class="w-4 h-4 mr-1" /> Approve
                </button>
            </form>
            <form action="{{ route('vendor.admission.application.update-status', [$admission, $application]) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded flex items-center text-sm font-semibold">
                    <x-lucide-x class="w-4 h-4 mr-1" /> Reject
                </button>
            </form>
        </div>
        @endif

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
            <div>
                <p class="text-gray-500 font-medium">Program</p>
                <p class="text-gray-800">{{ optional($application->program)->name }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-gray-500 font-medium">Notes</p>
                <div class="text-gray-800 mt-1 no-tailwind">
                    {!! nl2br(e($application->notes)) !!}
                </div>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 font-medium mb-2">Academic Documents</p>
                @php
                $docs = is_array($application->academic_documents) ? $application->academic_documents : json_decode($application->academic_documents, true);
                @endphp
                @if($docs && count($docs) > 0)
                <div class="space-y-2">
                    @foreach($docs as $doc)
                    <img src="{{ Storage::url($doc) }}" alt="Academic Document" class="rounded shadow border border-gray-200" />
                    @endforeach
                </div>
                @else
                <p class="text-gray-800">No academic documents provided.</p>
                @endif
            </div>
        </div>
    </div>
    @endsection