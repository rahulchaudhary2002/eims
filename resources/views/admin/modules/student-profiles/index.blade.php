@extends('admin.layouts.app')
@section('title', 'Student Profiles')
@section('page-title', 'Student Profiles')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Student Profiles" subtitle="Manage student profile information"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Student Profiles']]">
        <x-slot:actions>
            <a href="{{ route('admin.student-profiles.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Profile
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.student-profiles.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="w-56">
                <label class="form-label text-xs">Student</label>
                <select name="student_id" class="form-control">
                    <option value="">All Students</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <label class="form-label text-xs">Province</label>
                <select name="province" class="form-control">
                    <option value="">All Provinces</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p }}" {{ request('province') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <label class="form-label text-xs">District</label>
                <select name="district" class="form-control">
                    <option value="">All Districts</option>
                    @foreach($districts as $d)
                        <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <label class="form-label text-xs">City</label>
                <select name="city" class="form-control">
                    <option value="">All Cities</option>
                    @foreach($cities as $c)
                        <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-32">
                <label class="form-label text-xs">Budget Min (NPR)</label>
                <input type="number" name="budget_min" class="form-control"
                    value="{{ request('budget_min') }}" min="0" step="1000" placeholder="0">
            </div>

            <div class="w-32">
                <label class="form-label text-xs">Budget Max (NPR)</label>
                <input type="number" name="budget_max" class="form-control"
                    value="{{ request('budget_max') }}" min="0" step="1000" placeholder="Any">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.student-profiles.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Province</th>
                        <th>District / City</th>
                        <th>Budget (NPR)</th>
                        <th>Career Interests</th>
                        <th>Preferred Faculties</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profiles as $profile)
                    <tr>
                        <td class="text-slate-400 text-sm">{{ $profiles->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-medium text-slate-800">{{ $profile->student->name }}</div>
                            <div class="text-xs text-slate-400">{{ $profile->student->email }}</div>
                        </td>
                        <td>{{ $profile->province ?? '-' }}</td>
                        <td>
                            @if($profile->district || $profile->city)
                                {{ implode(', ', array_filter([$profile->district, $profile->city])) }}
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            @if($profile->budget_min !== null || $profile->budget_max !== null)
                                {{ number_format($profile->budget_min ?? 0) }}
                                -
                                {{ $profile->budget_max ? number_format($profile->budget_max) : '∞' }}
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td>
                            @php $ci = $profile->career_interests ?? [] @endphp
                            @if(count($ci))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($ci, 0, 3) as $tag)
                                        <span class="badge badge-blue text-xs">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($ci) > 3)
                                        <span class="text-xs text-slate-400">+{{ count($ci) - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td>
                            @php $pf = $profile->preferred_faculties ?? [] @endphp
                            @if(count($pf))
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($pf, 0, 3) as $tag)
                                        <span class="badge badge-green text-xs">{{ $tag }}</span>
                                    @endforeach
                                    @if(count($pf) > 3)
                                        <span class="text-xs text-slate-400">+{{ count($pf) - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('admin.student-profiles.show', $profile) }}"
                                    class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.student-profiles.edit', $profile) }}"
                                    class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                </a>
                                <form action="{{ route('admin.student-profiles.destroy', $profile) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Delete this student profile?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            No student profiles found.
                            <a href="{{ route('admin.student-profiles.create') }}" class="text-blue-600 hover:underline ml-1">Add the first one.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($profiles->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $profiles->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
