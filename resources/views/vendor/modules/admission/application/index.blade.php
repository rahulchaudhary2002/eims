@extends('vendor.layouts.app')
@section('title', 'Admission Applications')

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📄 Applications for: {{ $admission->title }}</h1>
            <p class="text-gray-600 mt-1">View all applications submitted for this admission</p>
        </div>
        <a href="{{ route('vendor.admission.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg shadow flex items-center">
            <x-lucide-arrow-left class="w-4 h-4 mr-2" /> Back to Admissions
        </a>
    </div>

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applicant Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Submitted At</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($applications as $application)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->full_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->program->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($application->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" /> Approved
                                </span>
                                @elseif($application->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <x-lucide-x-circle class="w-3 h-3 mr-1" /> Rejected
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <x-lucide-clock class="w-3 h-3 mr-1" /> Pending
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendor.admission.application.show', [$admission, $application]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-eye class="w-4 h-4 mr-1" /> View
                                    </a>
                                    @if($application->status === 'pending')
                                    <form action="{{ route('vendor.admission.application.update-status', [$admission, $application]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                            <x-lucide-check class="w-4 h-4 mr-1" /> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('vendor.admission.application.update-status', [$admission, $application]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                            <x-lucide-x class="w-4 h-4 mr-1" /> Reject
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-inbox class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg">No applications found</p>
                                <p class="text-sm">No one has applied for this admission yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection