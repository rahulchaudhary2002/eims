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
                <svg class="w-28 h-2 overflow-hidden rounded-full" viewBox="0 0 100 8" preserveAspectRatio="none" aria-hidden="true">
                    <rect width="100" height="8" rx="4" fill="rgba(255, 255, 255, 0.2)"></rect>
                    <rect width="{{ $profileCompletion }}" height="8" rx="4" fill="#ffffff"></rect>
                </svg>
                <span class="text-sm font-bold text-white">{{ $profileCompletion }}% complete</span>
            </div>
        </div>
    </div>
</section>

{{-- Content --}}
<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-8">

        @if(session('success'))
        <div class="student-form-info text-green-700 border-green-200 bg-green-50">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- Basic Info --}}
        <div class="student-form-card">
            <div class="student-form-header">
                <h2 class="student-form-title">Basic Information</h2>
                <p class="student-form-description">Keep your core profile details aligned with the same clean design system used for application and inquiry forms.</p>
            </div>
            <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
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
                        <label class="student-form-label">Profile Photo</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="student-form-file">
                        <p class="student-form-help">JPG, PNG or GIF. Max 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="student-form-label">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $student->name) }}"
                               class="student-form-control">
                        @error('name')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $student->email) }}"
                               class="student-form-control">
                        @error('email')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                               class="student-form-control">
                    </div>
                    <div>
                        <label class="student-form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               class="student-form-control">
                    </div>
                    <div>
                        <label class="student-form-label">Gender</label>
                        <select name="gender" class="student-form-control student-form-select">
                            <option value="">Select gender</option>
                            <option value="male" {{ old('gender', $student->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $student->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div class="student-form-actions">
                    <button type="submit"
                        class="student-form-btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Additional Details --}}
        <div class="student-form-card">
            <div class="student-form-header">
                <h2 class="student-form-title">Additional Details</h2>
                <p class="student-form-description">Complete your supporting profile information using the same improved spacing and field consistency.</p>
            </div>
            <form method="POST" action="{{ route('student.profile.update-extended') }}" class="space-y-5">
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
                        <label class="student-form-label">{{ $label }}</label>
                        <input type="{{ $type }}" name="{{ $field }}"
                               value="{{ old($field, $student->profile?->$field) }}"
                               class="student-form-control">
                    </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <label class="student-form-label">Address</label>
                        <textarea name="address" rows="2"
                                  class="student-form-control student-form-textarea">{{ old('address', $student->profile?->address) }}</textarea>
                    </div>
                </div>
                <div class="student-form-actions">
                    <button type="submit"
                        class="student-form-btn-primary">
                        <i class="fas fa-save"></i> Save Details
                    </button>
                </div>
            </form>
        </div>

        {{-- Academic Records Summary --}}
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 gap-3">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Academic Records</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Add and manage your education history, transcripts, and qualification details</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('student.academic-records.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-[#2c5aa0] text-white rounded-lg hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-plus"></i> Add
                    </a>
                    <a href="{{ route('student.academic-records.index') }}" class="text-sm text-[#4299e1] hover:underline font-semibold no-underline">Manage</a>
                </div>
            </div>
            @if($student->academicRecords->count())
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
            @else
                <div class="px-6 py-10 text-center">
                    <div class="w-14 h-14 rounded-full bg-[#ebf8ff] flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-graduation-cap text-xl text-[#2c5aa0]"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">No academic records added yet</p>
                    <p class="text-xs text-gray-400 mt-1">Add your school, college, transcript, and qualification details from your profile.</p>
                    <a href="{{ route('student.academic-records.create') }}" class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-plus"></i> Add Academic Record
                    </a>
                </div>
            @endif
        </div>

        {{-- Documents Summary --}}
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 gap-3">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Documents</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Upload and manage your identity and supporting documents</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('student.documents.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold bg-[#2c5aa0] text-white rounded-lg hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-upload"></i> Upload
                    </a>
                    <a href="{{ route('student.documents.index') }}" class="text-sm text-[#4299e1] hover:underline font-semibold no-underline">Manage</a>
                </div>
            </div>

            @if($student->documents->count())
                <div class="divide-y divide-gray-50">
                    @foreach($student->documents->take(3) as $document)
                    <div class="flex items-center gap-3 px-6 py-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                            <i class="fas fa-file-alt text-sky-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-700 truncate">{{ $document->title }}</p>
                            <p class="text-xs text-gray-400 truncate">
                                {{ \App\Models\StudentDocument::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type }}
                                · {{ $document->created_at->format('M d, Y') }}
                            </p>
                        </div>
                        @if($document->file_path)
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-xs font-semibold text-[#4299e1] hover:underline no-underline shrink-0">View</a>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-10 text-center">
                    <div class="w-14 h-14 rounded-full bg-[#ebf8ff] flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-upload text-xl text-[#2c5aa0]"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-700">No documents uploaded yet</p>
                    <p class="text-xs text-gray-400 mt-1">Add citizenship, transcripts, certificates, or other supporting files from your profile.</p>
                    <a href="{{ route('student.documents.create') }}" class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-upload"></i> Upload Document
                    </a>
                </div>
            @endif
        </div>

    </div>
</section>

@endsection
