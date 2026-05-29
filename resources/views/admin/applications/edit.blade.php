@extends('admin.layouts.app')
@section('title', 'Edit Application')
@section('page-title', 'Edit Application')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Edit Application"
        :subtitle="$application->application_number"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Applications','route'=>'admin.applications.index'],
            ['label'=>$application->application_number],
            ['label'=>'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.applications.update', $application) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="eims-card p-6">
            <h2 class="font-semibold text-slate-700 mb-5">Application Details</h2>
            @include('admin.applications.partials.form')
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
