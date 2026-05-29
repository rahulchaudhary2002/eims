@extends('admin.layouts.app')
@section('title', 'Compare Item #' . $studentCompareItem->id)
@section('page-title', 'Compare Item Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Compare Item #{{ $studentCompareItem->id }}"
        subtitle="{{ $studentCompareItem->student->name ?? 'Student' }} → {{ $studentCompareItem->institution->name ?? 'Institution' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Compare Items', 'route' => 'admin.student-compare-items.index'],
            ['label' => 'Item #' . $studentCompareItem->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-compare-items.edit', $studentCompareItem) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.student-compare-items.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Details --}}
        <div class="lg:col-span-2">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Compare Item Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student</dt>
                        <dd class="mt-1">
                            @if($studentCompareItem->student)
                                <a href="{{ route('admin.students.show', $studentCompareItem->student) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $studentCompareItem->student->name }}
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $studentCompareItem->student->email }}</div>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1">
                            @if($studentCompareItem->institution)
                                <a href="{{ route('admin.institutions.show', $studentCompareItem->institution) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $studentCompareItem->institution->name }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Program</dt>
                        <dd class="mt-1 text-slate-700">
                            @if($studentCompareItem->institutionProgram)
                                <a href="{{ route('admin.institution-programs.show', $studentCompareItem->institutionProgram) }}" class="text-blue-600 hover:underline">
                                    {{ $studentCompareItem->institutionProgram->title ?: ($studentCompareItem->institutionProgram->program->name ?? '—') }}
                                </a>
                            @else
                                <span class="text-slate-400">Not specified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Added At</dt>
                        <dd class="mt-1 text-slate-700">{{ $studentCompareItem->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.student-compare-items.edit', $studentCompareItem) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Item
                    </a>
                    <form action="{{ route('admin.student-compare-items.destroy', $studentCompareItem) }}" method="POST" onsubmit="return confirm('Remove this compare item? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Remove Item
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
