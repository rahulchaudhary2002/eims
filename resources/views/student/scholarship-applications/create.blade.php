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
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                <form method="POST" action="{{ route('student.scholarship-applications.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Scholarship <span class="text-red-500">*</span></label>
                        <select name="scholarship_id" class="w-full px-4 py-3 text-sm border {{ $errors->has('scholarship_id') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">Select scholarship</option>
                            @foreach($scholarships as $sc)
                                <option value="{{ $sc->id }}" {{ $selected?->id == $sc->id || old('scholarship_id') == $sc->id ? 'selected' : '' }}>{{ $sc->title }}</option>
                            @endforeach
                        </select>
                        @error('scholarship_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    @if($myApplications->count())
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Link to Application (optional)</label>
                        <select name="application_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
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
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks / Notes</label>
                        <textarea name="remarks" rows="4"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]"
                                  placeholder="Explain why you deserve this scholarship...">{{ old('remarks') }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('student.scholarship-applications.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                            <i class="fas fa-graduation-cap"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
