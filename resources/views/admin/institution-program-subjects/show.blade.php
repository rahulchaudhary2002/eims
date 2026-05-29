@extends('admin.layouts.app')
@section('title', $institutionProgramSubject->subject_name)
@section('page-title', 'Program Subject')

@section('content')
@php
    $subject = $institutionProgramSubject;
    $ip      = $subject->institutionProgram;
@endphp
<div class="space-y-5">

    <x-admin.page-header
        :title="$subject->subject_name"
        :subtitle="($ip->institution->name ?? '—') . ' — ' . ($ip->program->name ?? '—')"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Program Subjects','route'=>'admin.institution-program-subjects.index'],
            ['label'=>$subject->subject_name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-program-subjects.edit', $subject) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.institution-program-subjects.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left sidebar --}}
        <div class="space-y-5">

            {{-- Type card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Subject Type</h3>
                @if($subject->is_optional)
                    <span class="badge badge-blue">Optional</span>
                @else
                    <span class="badge badge-orange">Required</span>
                @endif
            </div>

            {{-- Meta --}}
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-2">Meta</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-0.5">Added</dt>
                        <dd class="text-slate-700">{{ $subject->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-0.5">Last Updated</dt>
                        <dd class="text-slate-700">{{ $subject->updated_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Main --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Details --}}
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Subject Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs mb-1">Subject Name</dt>
                        <dd class="text-slate-800 font-semibold text-base">{{ $subject->subject_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd>
                            @if($ip?->institution)
                                <a href="{{ route('admin.institutions.show', $ip->institution) }}" class="text-indigo-600 hover:underline">
                                    {{ $ip->institution->name }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Program</dt>
                        <dd>
                            @if($ip?->program)
                                <a href="{{ route('admin.programs.show', $ip->program) }}" class="text-indigo-600 hover:underline">
                                    {{ $ip->program->name }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution Program</dt>
                        <dd>
                            @if($ip)
                                <a href="{{ route('admin.institution-programs.show', $ip) }}" class="text-indigo-600 hover:underline">
                                    {{ $ip->title ?: ($ip->program->name ?? '—') }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    @if($ip?->program?->faculty)
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Faculty</dt>
                        <dd>{{ $ip->program->faculty->name }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Danger zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Permanently delete this subject. This cannot be undone.</p>
                <form action="{{ route('admin.institution-program-subjects.destroy', $subject) }}" method="POST"
                      onsubmit="return confirm('Delete this subject? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Subject
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
