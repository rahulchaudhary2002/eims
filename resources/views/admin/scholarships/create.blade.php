@extends('admin.layouts.app')
@section('title', 'Add Scholarship')
@section('page-title', 'Add Scholarship')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Add Scholarship"
        subtitle="Create a scholarship or cashback offer."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Scholarships','route'=>'admin.scholarships.index'],
            ['label'=>'Add Scholarship'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.scholarships.store') }}" method="POST">
        @csrf
        <div class="eims-card p-6">
            <h2 class="font-semibold text-slate-700 mb-5">Scholarship Details</h2>
            @include('admin.scholarships.partials.form', ['scholarship' => null])
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Scholarship</button>
            </div>
        </div>
    </form>
</div>
@endsection
