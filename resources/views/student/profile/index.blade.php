@extends('layouts.student')

@section('title', 'My Profile')

@section('content')

{{-- Hero --}}
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-5 mt-4">
            <div class="flex items-center gap-4">
                @if($student->avatar)
                    <img src="{{ Storage::url($student->avatar) }}" class="w-16 h-16 rounded-full object-cover border-4 border-white/30 shrink-0">
                @else
                    <div class="w-16 h-16 rounded-full bg-white/20 border-4 border-white/30 flex items-center justify-center text-2xl font-bold shrink-0">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">My Profile</h1>
                    <p class="text-white/70 text-sm mt-0.5">Manage your personal information</p>
                </div>
            </div>
            <div class="sm:ml-auto flex items-center gap-3 bg-white/15 rounded-xl px-5 py-3">
                <div class="w-28 h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-2 bg-white rounded-full" style="width: {{ $profileCompletion }}%"></div>
                </div>
                <span class="text-sm font-bold text-white">{{ $profileCompletion }}% complete</span>
            </div>
        </div>
    </div>
</section>

{{-- Content --}}
<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-8">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">Basic Information</h2>
            </div>
            <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf @method('PUT')

                {{-- Avatar --}}
                <div class="flex items-center gap-5">
                    @if($student->avatar)
                        <img src="{{ Storage::url($student->avatar) }}" class="w-20 h-20 rounded-full object-cover ring-4 ring-gray-100">
                    @else
                        <div class="w-20 h-20 rounded-full bg-[#ebf8ff] flex items-center justify-center ring-4 ring-gray-100">
                            <span class="text-[#2c5aa0] text-3xl font-bold">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Profile Photo</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="block text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#ebf8ff] file:text-[#2c5aa0] hover:file:bg-[#bee3f8]">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG or GIF. Max 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Gender</label>
                        <select name="gender" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-3 rounded-xl hover:opacity-90 transition">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Additional Details --}}
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">Additional Details</h2>
            </div>
            <form method="POST" action="{{ route('student.profile.update-extended') }}" class="p-6 space-y-5">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach([
                        ['guardian_name', 'Guardian Name', 'text'],
                        ['guardian_phone', 'Guardian Phone', 'text'],
                        ['province', 'Province', 'text'],
                        ['district', 'District', 'text'],
                        ['city', 'City', 'text'],
                        ['preferred_location', 'Preferred Location', 'text'],
                        ['budget_min', 'Budget Min (NPR)', 'number'],
                        ['budget_max', 'Budget Max (NPR)', 'number'],
                    ] as [$field, $label, $type])
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">{{ $label }}</label>
                        <input type="{{ $type }}" name="{{ $field }}"
                               value="{{ old($field, $student->profile?->$field) }}"
                               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">
                    </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Address</label>
                        <textarea name="address" rows="2"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] focus:ring-1 focus:ring-[#4299e1]/20">{{ old('address', $student->profile?->address) }}</textarea>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-3 rounded-xl hover:opacity-90 transition">
                        <i class="fas fa-save"></i> Save Details
                    </button>
                </div>
            </form>
        </div>

        {{-- Academic Records Summary --}}
        @if($student->academicRecords->count())
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">Academic Records</h2>
                <a href="{{ route('student.academic-records.index') }}" class="text-sm text-[#4299e1] hover:underline font-semibold no-underline">Manage</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($student->academicRecords->take(3) as $record)
                <div class="flex items-center gap-3 px-6 py-3">
                    <div class="w-2 h-2 rounded-full {{ $record->is_verified ? 'bg-green-400' : 'bg-yellow-400' }} shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-700">{{ \App\Models\StudentAcademicRecord::LEVELS[$record->level] ?? $record->level }}</p>
                        <p class="text-xs text-gray-400">{{ $record->institution_name }} · {{ $record->passed_year }}</p>
                    </div>
                    <span class="text-xs font-semibold {{ $record->is_verified ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $record->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
