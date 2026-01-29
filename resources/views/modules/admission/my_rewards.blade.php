@extends('layouts.app')

@section('title', 'My Admission Rewards')

@section('content')
<div class="max-w-7xl mx-auto lg:grid lg:grid-cols-4 gap-6 px-4 sm:px-6 lg:px-8">
    @include('includes.sidebar')

    <!-- Main Content -->
    <div class="lg:col-span-3 flex flex-col gap-6">

        <!-- Admission Rewards -->
        <section id="rewards" class="bg-white shadow rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">My Admission Rewards</h3>

            @if($rewards->isEmpty())
            <p class="text-gray-500">You have not submitted any admission reward requests yet.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Receipt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course/Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Institution</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reward</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rewards as $index => $reward)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <a href="{{ asset('storage/' . $reward->admission_receipt) }}" target="_blank" class="text-indigo-600 hover:underline">View Receipt</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reward->admissionApplication->course->name ?? $reward->admissionApplication->grade ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reward->admissionApplication->admission->institution->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $reward->reward ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                'approved' => 'bg-green-100 text-green-800',
                                ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$reward->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reward->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reward->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </section>
    </div>
</div>
@endsection
