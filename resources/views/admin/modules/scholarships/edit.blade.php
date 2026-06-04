@extends('admin.layouts.app')
@section('title', 'Edit Scholarship')
@section('page-title', 'Edit Scholarship')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Edit Scholarship"
        :subtitle="$scholarship->title"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Scholarships','route' => 'admin.scholarships.index'],
            ['label'=>$scholarship->title],
            ['label'=>'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.scholarships.update', $scholarship) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="eims-card p-6">
            <h2 class="font-semibold text-slate-700 mb-5">Scholarship Details</h2>
            @include('admin.modules.scholarships.partials.form')
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
