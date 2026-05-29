@extends('admin.layouts.app')
@section('title', 'Upload Document')
@section('page-title', 'Upload Document')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Upload Document" subtitle="Upload a new student document"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Student Documents','route'=>'admin.student-documents.index'],
            ['label'=>'Upload'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-documents.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="danger" :message="session('error')" />

    <form action="{{ route('admin.student-documents.store') }}" method="POST"
          enctype="multipart/form-data" class="space-y-5">
        @csrf

        @include('admin.student-documents.partials.form')

        <div class="flex justify-end gap-3 pb-4">
            <a href="{{ route('admin.student-documents.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                Upload Document
            </button>
        </div>
    </form>

</div>
@endsection
