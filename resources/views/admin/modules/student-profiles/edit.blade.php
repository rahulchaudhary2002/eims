@extends('admin.layouts.app')
@section('title', 'Edit Profile — ' . $studentProfile->student->name)
@section('page-title', 'Edit Profile — ' . $studentProfile->student->name)

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Edit Profile"
        :subtitle="'Editing profile for ' . $studentProfile->student->name"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Student Profiles','route' => 'admin.student-profiles.index'],
            ['label'=>$studentProfile->student->name,'route' => 'admin.student-profiles.show','params'=>[$studentProfile]],
            ['label'=>'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-profiles.show', $studentProfile) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="danger" :message="session('error')" />

    <form action="{{ route('admin.student-profiles.update', $studentProfile) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        @include('admin.modules.student-profiles.partials.form')

        <div class="flex justify-end gap-3 pb-4">
            <a href="{{ route('admin.student-profiles.show', $studentProfile) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Update Profile
            </button>
        </div>
    </form>

</div>
@endsection
