@extends('layouts.student')

@section('title', 'Upload Document')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.documents.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Upload Document</h1>
                <p class="text-white/70 text-sm mt-1">Add a new document to your profile</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                <form method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @include('student.documents.partials.form')
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('student.documents.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
