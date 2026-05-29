@extends('institution.layouts.app')
@section('title', 'Select Institution')
@section('page-title', 'Select Institution')

@section('content')
<div class="max-w-4xl mx-auto space-y-5">
    <div class="eims-card p-6">
        <h1 class="text-xl font-bold text-slate-800">Select active institution</h1>
        <p class="text-sm text-slate-500 mt-1">Choose the institution you want to manage in this session.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($institutions as $institution)
            <form method="POST" action="{{ route('institution.select.store') }}" class="eims-card p-5 hover:border-primary-200 transition-colors">
                @csrf
                <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center shrink-0 text-primary-700 font-bold">
                        {{ strtoupper(substr($institution->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-semibold text-slate-800 truncate">{{ $institution->name }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}</p>
                        <button class="btn btn-primary btn-sm mt-4">Use this institution</button>
                    </div>
                </div>
            </form>
        @empty
            <div class="eims-card p-8 text-center text-slate-500 md:col-span-2">No active institution assignments were found.</div>
        @endforelse
    </div>
</div>
@endsection
