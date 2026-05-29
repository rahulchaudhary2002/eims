@extends('admin.layouts.app')
@section('title', 'Vendors')
@section('page-title', 'Vendors')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Vendors" subtitle="Manage service providers"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Vendors']]">
        <x-slot:actions>
            <a href="{{ route('admin.vendor.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Vendor
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    <div class="eims-card p-0 overflow-hidden">
        <div class="eims-table-wrapper">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Email Verified</th>
                        <th>Phone Verified</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->id }}</td>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>{{ $vendor->phone ?? '-' }}</td>
                        <td>
                            @if($vendor->email_verified_at)
                            <x-admin.status-badge :status="'active'" />
                            @else
                            <x-admin.status-badge :status="'inactive'" />
                            @endif
                        </td>
                        <td>
                            @if($vendor->phone_verified_at)
                            <x-admin.status-badge :status="'active'" />
                            @elseif($vendor->phone)
                            <x-admin.status-badge :status="'inactive'" />
                            @else
                            <span class="text-slate-400 text-sm">N/A</span>
                            @endif
                        </td>
                        <td class="actions-cell">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.vendor.show', $vendor) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.vendor.edit', $vendor) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>
                                <form action="{{ route('admin.vendor.destroy', $vendor) }}" method="POST" class="inline" onsubmit="return confirm('Delete this vendor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-admin.empty-state title="No vendors found" description="Get started by adding your first vendor." :action-href="route('admin.vendor.create')" action-label="Add Vendor" />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($vendors,'links') && $vendors->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $vendors->links() }}</div>
        @endif
    </div>

</div>
@endsection
