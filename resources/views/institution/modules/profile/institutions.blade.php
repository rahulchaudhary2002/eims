@extends('institution.layouts.app')
@section('title', 'Institutions')
@section('page-title', 'Assigned Institutions')

@section('content')
<div class="space-y-5">
    <x-admin.page-header title="Assigned Institutions" subtitle="Institutions assigned to your account."
        :breadcrumbs="[['label'=>'Institution','route'=>'institution.dashboard'],['label'=>'Assigned Institutions']]" />

    <div class="eims-card overflow-hidden">
    <div class="eims-table-wrapper">
        <table class="eims-table w-full">
            <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Primary</th><th>Role</th></tr></thead>
            <tbody>
            @foreach($institutions as $institution)
                <tr>
                    <td class="font-medium text-slate-800">{{ $institution->name }}</td>
                    <td>{{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}</td>
                    <td><span class="badge badge-blue">{{ $institution->status }}</span></td>
                    <td>{{ $institution->pivot->is_primary ? 'Yes' : 'No' }}</td>
                    <td>{{ \App\Models\UserInstitution::ROLES[$institution->pivot->role_name] ?? $institution->pivot->role_name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    </div>
</div>
@endsection
