@extends('institution.layouts.app')
@section('title', \Illuminate\Support\Str::plural($title))
@section('page-title', \Illuminate\Support\Str::plural($title))

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="\Illuminate\Support\Str::plural($title)"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => \Illuminate\Support\Str::plural($title)]]">
        <x-slot:actions>
        @if(Route::has("institution.{$routeBase}.create"))
            <a href="{{ route("institution.{$routeBase}.create") }}" class="btn btn-primary">
                <span class="text-base leading-none">+</span>
                Add {{ $title }}
            </a>
        @endif
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <div class="eims-card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-slate-800">{{ \Illuminate\Support\Str::plural($title) }}</h2>
            <span class="text-sm text-slate-500">{{ $records->total() }} total</span>
        </div>
        <div class="eims-table-wrapper">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        @foreach($fields as $field => $config)
                            <th>{{ $config['label'] ?? \Illuminate\Support\Str::headline($field) }}</th>
                        @endforeach
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            @foreach($fields as $field => $config)
                                <td class="text-sm">
                                    @include('institution.shared.resource-value', ['record' => $record, 'field' => $field])
                                </td>
                            @endforeach
                            <td class="text-right whitespace-nowrap">
                                @if(Route::has("institution.{$routeBase}.show"))
                                    <a href="{{ route("institution.{$routeBase}.show", $record) }}" class="btn btn-secondary btn-sm">View</a>
                                @endif
                                @if(Route::has("institution.{$routeBase}.edit"))
                                    <a href="{{ route("institution.{$routeBase}.edit", $record) }}" class="btn btn-primary btn-sm">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($fields) + 1 }}">
                                <x-admin.empty-state
                                    :title="'No ' . strtolower(\Illuminate\Support\Str::plural($title)) . ' found'"
                                    description="Records for the active institution will appear here." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
