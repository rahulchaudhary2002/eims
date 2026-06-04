@extends('layouts.student')

@section('title', 'Apply for Scholarship')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.scholarship-applications.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Apply for Scholarship</h1>
                <p class="text-white/70 text-sm mt-1">Submit your scholarship application</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Scholarship Application</h2>
                    <p class="student-form-description">Present scholarship requests with the same modern structure and field styling as the website application form.</p>
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

                <form method="POST" action="{{ route('student.scholarship-applications.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="student-form-label">Scholarship <span class="text-red-500">*</span></label>
                        <select name="scholarship_id" class="student-form-control student-form-select {{ $errors->has('scholarship_id') ? 'is-invalid' : '' }}">
                            <option value="">Select scholarship</option>
                            @foreach($scholarships as $sc)
                                <option value="{{ $sc->id }}" {{ $selected?->id == $sc->id || old('scholarship_id') == $sc->id ? 'selected' : '' }}>{{ $sc->title }}</option>
                            @endforeach
                        </select>
                        @error('scholarship_id')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    @if($myApplications->count())
                    <div>
                        <label class="student-form-label">Link to Application (optional)</label>
                        <select name="application_id" class="student-form-control student-form-select">
                            <option value="">No linked application</option>
                            @foreach($myApplications as $app)
                                <option value="{{ $app->id }}" {{ old('application_id') == $app->id ? 'selected' : '' }}>
                                    {{ $app->institution?->name }} - {{ $app->application_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div>
                        <label class="student-form-label">Remarks / Notes</label>
                        <textarea name="remarks" rows="4"
                                  class="student-form-control student-form-textarea"
                                  placeholder="Explain why you deserve this scholarship...">{{ old('remarks') }}</textarea>
                    </div>
                    <div class="student-form-actions">
                        <a href="{{ route('student.scholarship-applications.index') }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-graduation-cap"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
