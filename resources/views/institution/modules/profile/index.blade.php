@extends('institution.layouts.app')
@section('title', 'Profile')
@section('page-title', 'Institution Profile')

@section('content')
<form method="POST" action="{{ route('institution.profile.update') }}" class="space-y-5">
    @csrf
    @method('PUT')

    <x-admin.page-header
        :title="$institution->name"
        subtitle="Institution profile"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => 'Profile']]">
        <x-slot:actions>
            <button class="btn btn-primary">Save profile</button>
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <div class="eims-card p-6 space-y-5">
        <div>
            <h2 class="text-base font-semibold text-slate-800">Basic Information</h2>
            <p class="text-sm text-slate-500 mt-1">Verification and platform status are read-only in the institution dashboard.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach(['name' => 'Name', 'email' => 'Email', 'phone' => 'Phone', 'website' => 'Website', 'country' => 'Country', 'province' => 'Province', 'district' => 'District', 'city' => 'City', 'address' => 'Address'] as $field => $label)
                <div>
                    <label class="form-label">{{ $label }}</label>
                    <input name="{{ $field }}" value="{{ old($field, $institution->{$field}) }}" class="form-control @error($field) is-invalid @enderror">
                    @error($field)<p class="form-error">{{ $message }}</p>@enderror
                </div>
            @endforeach
            <div>
                <label class="form-label">Verification</label>
                <input value="{{ $institution->is_verified ? 'Verified' : 'Not verified' }}" class="form-control bg-slate-50" readonly>
            </div>
            <div>
                <label class="form-label">Status</label>
                <input value="{{ $institution->status }}" class="form-control bg-slate-50" readonly>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Short Description</label>
                <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $institution->short_description) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5">{{ old('description', $institution->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="eims-card p-6 space-y-5">
        <h2 class="text-base font-semibold text-slate-800">Extended Profile</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach(['facilities', 'infrastructure', 'achievements', 'accreditations'] as $field)
                <div>
                    <label class="form-label">{{ \Illuminate\Support\Str::headline($field) }}</label>
                    <textarea name="{{ $field }}" class="form-control" rows="4">{{ old($field, implode("\n", $institution->profile?->{$field} ?? [])) }}</textarea>
                </div>
            @endforeach
            @foreach(['facebook_url', 'instagram_url', 'linkedin_url', 'youtube_url'] as $field)
                <div>
                    <label class="form-label">{{ \Illuminate\Support\Str::headline($field) }}</label>
                    <input name="{{ $field }}" value="{{ old($field, $institution->profile?->{$field}) }}" class="form-control">
                </div>
            @endforeach
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['has_hostel', 'has_transportation', 'has_library', 'has_lab', 'has_cafeteria', 'has_sports', 'has_scholarship'] as $field)
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="{{ $field }}" value="1" class="rounded border-slate-300" @checked(old($field, $institution->profile?->{$field}))>
                    {{ \Illuminate\Support\Str::headline(str_replace('has_', '', $field)) }}
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end">
        <button class="btn btn-primary">Save profile</button>
    </div>
</form>
@endsection
