@extends('layouts.student')

@section('title', 'Book Counseling Session')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.counseling-sessions.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Book Counseling Session</h1>
                <p class="text-white/70 text-sm mt-1">Request a session with a counselor</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Book Session</h2>
                    <p class="student-form-description">Schedule counseling with the same polished card, spacing, and field treatment used by the main website forms.</p>
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

                <form method="POST" action="{{ route('student.counseling-sessions.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="student-form-label">Institution (optional)</label>
                        <select name="institution_id" class="student-form-control student-form-select">
                            <option value="">Select institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $selected?->id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="student-form-label">Mode <span class="text-red-500">*</span></label>
                            <select name="mode" class="student-form-control student-form-select {{ $errors->has('mode') ? 'is-invalid' : '' }}">
                                @foreach(\App\Models\CounselingSession::MODES as $val => $label)
                                    <option value="{{ $val }}" {{ old('mode') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('mode')<p class="student-form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="student-form-label">Preferred Date & Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                   class="student-form-control {{ $errors->has('scheduled_at') ? 'is-invalid' : '' }}">
                            @error('scheduled_at')<p class="student-form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label class="student-form-label">Message</label>
                        <textarea name="student_message" rows="4" placeholder="What would you like to discuss?"
                                  class="student-form-control student-form-textarea">{{ old('student_message') }}</textarea>
                    </div>
                    <div class="student-form-actions">
                        <a href="{{ route('student.counseling-sessions.index') }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-calendar-check"></i> Book Session
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
