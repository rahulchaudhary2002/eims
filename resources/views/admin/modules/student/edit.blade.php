@extends('admin.layouts.app')
@section('title', 'Edit: ' . $student->name)
@section('page-title', 'Edit Student')

@section('content')
<div class="space-y-5">

    <x-admin.page-header :title="'Edit: ' . $student->name" subtitle="Update student account details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Students','route'=>'admin.students.index'],['label'=>$student->name,'route'=>'admin.students.show','routeParam'=>$student],['label'=>'Edit']]">
        <x-slot:actions>
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        @include('admin.modules.student.partials.form')

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.students.show', $student) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Changes
            </button>
        </div>
    </form>

</div>
@endsection
