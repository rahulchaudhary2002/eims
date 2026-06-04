@extends('admin.layouts.app')
@section('title', 'Edit Admission')
@section('page-title', 'Edit Admission')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Edit Admission"
        :subtitle="$admission->admission_number"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Admissions','route' => 'admin.admissions.index'],
            ['label'=>$admission->admission_number],
            ['label'=>'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.admissions.show', $admission) }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.admissions.update', $admission) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="eims-card p-6">
            @include('admin.modules.admissions.partials.form')
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.admissions.show', $admission) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
