@extends('admin.layouts.app')
@section('title', 'Add Admission')
@section('page-title', 'Add Admission')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Add Admission"
        subtitle="Create an admission record from an application."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Admissions','route' => 'admin.admissions.index'],
            ['label'=>'Add Admission'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.admissions.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="eims-card p-6">
            <h2 class="font-semibold text-slate-700 mb-5">Admission Details</h2>
            @include('admin.modules.admissions.partials.form', ['admission' => null])
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Admission</button>
            </div>
        </div>
    </form>
</div>
@endsection
