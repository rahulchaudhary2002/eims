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
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                <form method="POST" action="{{ route('student.counseling-sessions.update', $counselingSession) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Institution</label>
                        <select name="institution_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">No institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $counselingSession->institution_id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Mode <span class="text-red-500">*</span></label>
                            <select name="mode" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
                                @foreach(\App\Models\CounselingSession::MODES as $val => $label)
                                    <option value="{{ $val }}" {{ old('mode', $counselingSession->mode) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">New Date & Time <span class="text-red-500">*</span></label>
                            <input type="datetime-local" name="scheduled_at"
                                   value="{{ old('scheduled_at', $counselingSession->scheduled_at->format('Y-m-d\TH:i')) }}"
                                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Message</label>
                        <textarea name="student_message" rows="3"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">{{ old('student_message', $counselingSession->student_message) }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('student.counseling-sessions.show', $counselingSession) }}"
                           class="px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                            <i class="fas fa-calendar"></i> Reschedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
