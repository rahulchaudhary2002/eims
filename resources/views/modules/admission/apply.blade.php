@extends('layouts.app')

@section('title', 'Apply for ' . $admission->title)

@section('content')

<!-- Institution Header -->
<div class="relative rounded-3xl overflow-hidden border border-gray-200 shadow-lg mb-10">
    <!-- Cover Image -->
    <div class="h-48 sm:h-60 bg-gray-100 relative">
        @if($admission->institution && $admission->institution->cover_image)
        <img src="{{ Storage::url($admission->institution->cover_image) }}"
            alt="Cover Image"
            class="h-full w-full object-cover">
        @else
        <div class="h-full w-full bg-gradient-to-r from-orange-100 to-orange-200"></div>
        @endif
    </div>

    <!-- Logo and Info -->
    <div class="relative flex items-center gap-4 px-6 sm:px-10 py-6">
        <!-- Logo -->
        <div class="h-24 w-24 sm:h-28 sm:w-28 rounded-2xl bg-white shadow-lg border flex items-center justify-center overflow-hidden">
            @if($admission->institution && $admission->institution->logo)
            <img src="{{ Storage::url($admission->institution->logo) }}"
                class="h-full w-full object-contain p-2"
                alt="Logo">
            @else
            <x-lucide-school class="w-12 h-12 text-gray-400" />
            @endif
        </div>

        <!-- Name & Info -->
        <div class="space-y-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                {{ $admission->institution->name ?? '' }}
                <x-lucide-badge-check class="w-5 h-5 text-blue-600" />
            </h1>

            @if($admission->institution && $admission->institution->established_at)
            <p class="text-sm sm:text-base text-gray-600">
                Established: {{ \Carbon\Carbon::parse($admission->institution->established_at)->format('Y') }}
            </p>
            @endif

            @if($admission->institution && $admission->institution->address)
            <p class="text-sm sm:text-base text-gray-600 flex items-center gap-1">
                <x-lucide-map-pin class="w-4 h-4 text-gray-400" />
                {{ $admission->institution->address }}
            </p>
            @endif
        </div>
    </div>
</div>

<!-- Application Form -->
<div class="max-w-3xl mx-auto mb-20">

    <div class="bg-white rounded-2xl shadow-md border p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Apply for: {{ $admission->title }}
        </h1>

        @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('admission.apply.store', $admission->slug) }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="full_name">Full Name *</label>
                <input type="text" name="full_name" id="full_name"
                    value="{{ old('full_name') }}"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('full_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="email">Email *</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="phone">Phone *</label>
                <input type="text" name="phone" id="phone"
                    value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Program Selection (if applicable) -->
            @if($admission->programs->count())
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="program_id">Select Program *</label>
                <select name="program_id" id="program_id"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    required>
                    <option value="">-- Choose a program --</option>
                    @foreach($admission->programs as $program)
                    <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                        {{ $program->display_name }}
                    </option>
                    @endforeach
                </select>
                @error('program_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            <!-- Academic Documents -->
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="academic_documents">Academic Documents (PDF, JPG, PNG) *</label>
                <input type="file" name="academic_documents[]" id="academic_documents"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    accept=".pdf,.jpg,.jpeg,.png"
                    multiple
                    required>
                @error('academic_documents')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @if($errors->has('academic_documents.*'))
                @foreach($errors->get('academic_documents.*') as $messages)
                @foreach($messages as $message)
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @endforeach
                @endforeach
                @endif
            </div>

            <!-- Notes (optional) -->
            <div>
                <label class="block text-gray-700 font-medium mb-2" for="notes">Additional Notes (Optional)</label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl hover:bg-indigo-700 transition">
                    Submit Application
                </button>
            </div>

        </form>
    </div>
</div>
@endsection