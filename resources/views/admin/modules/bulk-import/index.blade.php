@extends('admin.layouts.app')
@section('title', 'Bulk Excel Import')
@section('page-title', 'Bulk Excel Import')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Bulk Excel Import" subtitle="Import multiple admin modules at once from one Excel file."
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Bulk Import']]">
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2">
            <x-admin.form-card title="Upload Excel">
                <p class="text-sm text-slate-600 mb-5">Supported sheet names: <strong>Affiliations</strong>, <strong>Institution Types</strong>, <strong>Institution Categories</strong>, <strong>Program Categories</strong>, <strong>Levels</strong>, <strong>Institutions</strong>, <strong>Programs</strong>, <strong>Courses</strong>.</p>

                <form action="{{ route('admin.bulk-import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="file" class="block text-sm font-medium text-slate-700 mb-1.5">Excel File (.xlsx)</label>
                        <input
                            id="file"
                            name="file"
                            type="file"
                            accept=".xlsx"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500"
                            required>
                        @error('file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="submit" class="btn btn-primary">
                            <x-lucide-upload class="w-4 h-4" />
                            Import Data
                        </button>
                    </div>
                </form>
            </x-admin.form-card>
        </div>

        <div class="eims-card p-6">
            <h2 class="text-base font-semibold text-slate-800 mb-2">Sample Template</h2>
            <p class="text-sm text-slate-600 mb-4">Download and fill this sample workbook, then upload it here.</p>
            <div class="space-y-3">
                <a href="{{ route('admin.bulk-import.template') }}" class="btn btn-primary w-full justify-center">
                    <x-lucide-download class="w-4 h-4" />
                    Download Sample Excel
                </a>
                <a href="{{ asset('samples/admin-module-bulk-import-sample.xlsx') }}" class="btn btn-secondary w-full justify-center">
                    <x-lucide-file-text class="w-4 h-4" />
                    Direct Sample (Fallback)
                </a>
            </div>
        </div>

    </div>

    @php
        $summary = session('import_summary', []);
        $skipped = session('import_skipped', []);
    @endphp

    @if(!empty($summary))
    <div class="eims-card p-6">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Import Summary</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($summary as $module => $count)
            <div class="rounded-lg border border-slate-200 p-4 bg-slate-50 text-center">
                <p class="text-xs uppercase tracking-wide text-slate-500 mb-1">{{ str_replace('_', ' ', $module) }}</p>
                <p class="text-2xl font-bold text-slate-800">{{ $count }}</p>
                <p class="text-xs text-slate-500">rows imported</p>
            </div>
            @endforeach
        </div>

        @if(!empty($skipped))
        <div class="mt-5 alert alert-danger">
            <h3 class="font-semibold mb-2">Skipped Rows</h3>
            <ul class="list-disc list-inside text-sm space-y-1 max-h-48 overflow-y-auto">
                @foreach($skipped as $message)
                <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection
