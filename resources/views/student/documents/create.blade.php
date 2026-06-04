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
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Document Upload</h2>
                    <p class="student-form-description">Use the same polished document form pattern as the main inquiry and application pages.</p>
                </div>

                @if ($errors->any())
                    <div class="student-form-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @include('student.documents.partials.form')
                    <div class="student-form-actions">
                        <a href="{{ route('student.documents.index') }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
