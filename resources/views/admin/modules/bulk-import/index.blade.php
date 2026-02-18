@extends('admin.layouts.app')
@section('title', 'Bulk Excel Import')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Bulk Excel Import</h1>
    <p class="text-gray-600 mt-2">Import multiple admin modules at once from one Excel file.</p>
</div>

@if(session('success'))
<div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
    {{ session('error') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Upload Excel</h2>
        <p class="text-sm text-gray-600 mb-5">Supported sheet names: <strong>Affiliations</strong>, <strong>Institution Types</strong>, <strong>Institution Categories</strong>, <strong>Program Categories</strong>, <strong>Levels</strong>, <strong>Institutions</strong>, <strong>Programs</strong>, <strong>Courses</strong>.</p>

        <form action="{{ route('admin.bulk-import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Excel File (.xlsx)</label>
                <input
                    id="file"
                    name="file"
                    type="file"
                    accept=".xlsx"
                    class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:ring-blue-500"
                    required>
                @error('file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors">
                <x-lucide-upload class="w-4 h-4 mr-2" />
                Import Data
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Sample Template</h2>
        <p class="text-sm text-gray-600 mb-4">Download and fill this sample workbook, then upload it here.</p>
        <a href="{{ route('admin.bulk-import.template') }}"
            class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-medium hover:bg-black transition-colors">
            <x-lucide-download class="w-4 h-4 mr-2" />
            Download Sample Excel
        </a>
        <a href="{{ asset('samples/admin-module-bulk-import-sample.xlsx') }}"
            class="inline-flex items-center px-4 py-2 mt-3 rounded-lg border border-gray-300 text-gray-800 text-sm font-medium hover:bg-gray-50 transition-colors">
            <x-lucide-file-text class="w-4 h-4 mr-2" />
            Direct Sample (Fallback)
        </a>
    </div>
</div>

@php
    $summary = session('import_summary', []);
    $skipped = session('import_skipped', []);
@endphp

@if(!empty($summary))
<div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Import Summary</h2>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach($summary as $module => $count)
        <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
            <p class="text-xs uppercase tracking-wide text-gray-500">{{ str_replace('_', ' ', $module) }}</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $count }}</p>
            <p class="text-xs text-gray-500">rows imported</p>
        </div>
        @endforeach
    </div>

    @if(!empty($skipped))
    <div class="mt-5 border border-amber-200 bg-amber-50 rounded-lg p-4">
        <h3 class="font-semibold text-amber-800 mb-2">Skipped Rows</h3>
        <ul class="list-disc list-inside text-sm text-amber-900 space-y-1 max-h-48 overflow-y-auto">
            @foreach($skipped as $message)
            <li>{{ $message }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endif
@endsection
