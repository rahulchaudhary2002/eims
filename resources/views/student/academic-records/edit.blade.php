@extends('layouts.student')

@section('title', 'Edit Academic Record')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.academic-records.index') }}" class="text-white/70 hover:text-white no-underline">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Edit Academic Record</h1>
                <p class="text-white/70 text-sm mt-1">Update your educational qualification</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">

            {{-- Form --}}
            <div class="min-w-0">
                <div class="student-form-card">
                    <div class="student-form-header">
                        <h2 class="student-form-title">Edit Academic Record</h2>
                        <p class="student-form-description">Update your academic information.</p>
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

                    <form method="POST" action="{{ route('student.academic-records.update', $academicRecord) }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')
                        @include('student.academic-records.partials.form', ['record' => $academicRecord])
                        <div class="student-form-actions">
                            <a href="{{ route('student.academic-records.index') }}" class="student-form-btn-secondary">Cancel</a>
                            <button type="submit" class="student-form-btn-primary">
                                <i class="fas fa-save"></i> Update Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @include('student.academic-records.partials.sidebar')

        </div>
    </div>
</section>

@endsection
