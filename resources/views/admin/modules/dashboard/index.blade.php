@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-md p-5 shadow-sm flex items-center transition duration-300 hover:-translate-y-2">
            <div class="w-[60px] h-[60px] rounded-full flex items-center justify-center mr-4 text-2xl bg-[rgba(67,97,238,0.1)] text-primary">
                <x-lucide-users class="w-8 h-8" />
            </div>

            <div class="flex flex-col">
                <div class="text-dark text-3xl font-bold">{{ $studentCount }}</div>
                <div class="mt-2 text-gray-700 text-lg">Total Students</div>
            </div>
        </div>
        <div class="bg-white rounded-md p-5 shadow-sm flex items-center transition duration-300 hover:-translate-y-2">
            <div class="w-[60px] h-[60px] rounded-full flex items-center justify-center mr-4 text-2xl bg-[rgba(76,201,240,0.1)] text-success">
                <x-lucide-landmark class="w-8 h-8" />
            </div>

            <div class="flex flex-col">
                <div class="text-dark text-3xl font-bold">{{ $institutionCount }}</div>
                <div class="mt-2 text-gray-700 text-lg">Registered Institutions</div>
            </div>
        </div>

        <div class="bg-white rounded-md p-5 shadow-sm flex items-center transition duration-300 hover:-translate-y-2">
            <div class="w-[60px] h-[60px] rounded-full flex items-center justify-center mr-4 text-2xl bg-[rgba(247,37,133,0.1)] text-warning">
                <x-lucide-dollar-sign class="w-8 h-8" />
            </div>

            <div class="flex flex-col">
                <div class="text-dark text-3xl font-bold">Rs. {{ number_format($receivedComission, 2) }}</div>
                <div class="mt-2 text-gray-700 text-lg">Total Revenue</div>
            </div>
        </div>

        <div class="bg-white rounded-md p-5 shadow-sm flex items-center transition duration-300 hover:-translate-y-2">
            <div class="w-[60px] h-[60px] rounded-full flex items-center justify-center mr-4 text-2xl bg-[rgba(58,12,163,0.1)] text-secondary">
                <x-lucide-hand-coins class="w-8 h-8" />
            </div>

            <div class="flex flex-col">
                <div class="text-dark text-3xl font-bold">Rs. {{ number_format($pendingComission, 2) }}</div>
                <div class="mt-2 text-gray-700 text-lg">Pending Commissions</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-md p-5 shadow-sm">
        <div class="flex items-center justify-between flex-wrap border-b gap-4 pb-4">
            <h2 class="text-primary text-xl font-bold">Institution Types Overview</h2>
            <a href="{{ route('admin.institution.index') }}" class="text-primary text-sm">View All Institutions</a>
        </div>
        <div class="flex flex-wrap gap-4 mt-4">
            @foreach($institutionTypes as $type)
                <div class="flex-1 min-w-[220px] max-w-xs bg-gray-50 rounded-lg p-4 flex flex-col items-start shadow-sm border-l-4 border-primary/70 hover:shadow transition">
                    <div class="flex items-center mb-2">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center bg-primary/10 text-primary mr-2">
                            @switch($type->name)
                                @case('College')
                                    <x-lucide-landmark class="w-6 h-6" />
                                    @break
                                @case('Training Center')
                                    <x-lucide-monitor class="w-6 h-6" />
                                    @break
                                @case('School')
                                    <x-lucide-school class="w-6 h-6" />
                                    @break
                                @case('Montessori')
                                    <x-lucide-baby class="w-6 h-6" />
                                    @break
                                @default
                                    <x-lucide-graduation-cap class="w-6 h-6" />
                            @endswitch
                        </span>
                        <span class="font-bold text-primary text-lg">{{ $type->name }}</span>
                    </div>
                    <div class="text-gray-700 text-sm">{{ $type->institutions_count }} Institutions</div>
                    <div class="text-gray-700 text-sm">
                        {{ number_format($type->institutions->sum('students_count') ?? 0) }} Students
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-md p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between flex-wrap border-b gap-4 pb-4">
            <h2 class="text-primary text-xl font-bold">Recent Admissions Application</h2>
            <a href="#" class="text-primary text-sm">View All</a>
        </div>

        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applicant Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Institution</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Submitted At</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentAdmissionApplications as $application)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->full_name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->admission->institution->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->email ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $application->grade ?? $application->course->name }}</td>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-inbox class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg">No applications found</p>
                                <p class="text-sm">No one has applied for admission yet.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection