@extends('admin.layouts.app')
@section('title', 'Edit Institution Program')
@section('page-title', 'Edit Institution Program')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Edit Institution Program"
        :subtitle="($institutionProgram->institution->name ?? '') . ' · ' . ($institutionProgram->program->name ?? '')"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Institution Programs','route'=>'admin.institution-programs.index'],
            ['label'=>'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-programs.show', $institutionProgram) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="danger" :message="session('error')" />

    <form action="{{ route('admin.institution-programs.update', $institutionProgram) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        @php $selectedInstitutionId = null; $selectedProgramId = null; @endphp

        @include('admin.institution-programs.partials.form')

        <div class="flex justify-end gap-3 pb-4">
            <a href="{{ route('admin.institution-programs.show', $institutionProgram) }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Update Entry
            </button>
        </div>
    </form>

    <div class="eims-card p-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <h2 class="font-semibold text-slate-700">Subjects</h2>
            <a href="{{ route('admin.institution-program-subjects.create', ['institution_program_id' => $institutionProgram->id]) }}" class="btn btn-primary btn-sm">
                Add Subject
            </a>
        </div>

        @if($institutionProgram->subjects->isEmpty())
            <p class="text-sm text-slate-400">No subjects added yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table text-sm">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institutionProgram->subjects->sortBy('subject_name') as $subject)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $subject->subject_name }}</td>
                                <td>
                                    @if($subject->is_optional)
                                        <span class="badge badge-blue">Optional</span>
                                    @else
                                        <span class="badge badge-orange">Required</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ route('admin.institution-program-subjects.show', $subject) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.institution-program-subjects.edit', $subject) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
