@extends('layouts.student')

@section('title', 'Reschedule Session')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.counseling-sessions.show', $counselingSession) }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Reschedule Session</h1>
                <p class="text-white/70 text-sm mt-1">Choose a new date and time</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Reschedule Session</h2>
                    <p class="student-form-description">Choose a better slot with the same consistent form treatment used across the updated student dashboard.</p>
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

                <form method="POST" action="{{ route('student.counseling-sessions.update', $counselingSession) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="student-form-label">Institution</label>
                        <select name="institution_id" class="student-form-control student-form-select">
                            <option value="">No institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $counselingSession->institution_id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="student-form-label">Mode <span class="text-red-500">*</span></label>
                            <select name="mode" class="student-form-control student-form-select">
                                @foreach(\App\Models\CounselingSession::MODES as $val => $label)
                                    <option value="{{ $val }}" {{ old('mode', $counselingSession->mode) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="student-form-label">New Date & Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_at"
                                   value="{{ old('scheduled_at', $counselingSession->scheduled_at->format('Y-m-d\TH:i')) }}"
                                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                   class="student-form-control">
                        </div>
                    </div>
                    <div>
                        <label class="student-form-label">Message</label>
                        <textarea name="student_message" rows="3"
                                  class="student-form-control student-form-textarea">{{ old('student_message', $counselingSession->student_message) }}</textarea>
                    </div>
                    <div class="student-form-actions">
                        <a href="{{ route('student.counseling-sessions.show', $counselingSession) }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-calendar"></i> Reschedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
