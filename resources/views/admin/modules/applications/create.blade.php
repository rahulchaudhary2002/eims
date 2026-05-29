@extends('admin.layouts.app')
@section('title', 'Add Application')
@section('page-title', 'Add Application')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Add Application"
        subtitle="Create a student application."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Applications','route' => 'admin.applications.index'],
            ['label'=>'Add Application'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.applications.store') }}" method="POST">
        @csrf
        <div class="eims-card p-6">
            <h2 class="font-semibold text-slate-700 mb-5">Application Details</h2>
            @include('admin.modules.applications.partials.form', ['application' => null])
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Application</button>
            </div>
        </div>
    </form>
</div>
@endsection
