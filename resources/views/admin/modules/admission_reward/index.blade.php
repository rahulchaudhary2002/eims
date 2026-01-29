@extends('admin.layouts.app')
@section('title', 'Admission Rewards')

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🏢 Admission Rewards Management</h1>
            <p class="text-gray-600 mt-1">Manage admission rewards</p>
        </div>
    </div>

    @if(session('success'))
    <div class="px-6 pb-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-lucide-check-circle class="w-5 h-5 mr-2 text-green-600" />
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($rewards->isEmpty())
            <p class="text-gray-500">There are no admission reward requests yet.</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rewards as $index => $reward)
                        <tr x-data="{ open: false, reward: '{{ $reward->reward ?? '' }}' }">
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex gap-2">
                                @if($reward->status === 'pending')
                                <!-- Approve Button -->
                                <button @click="open = true" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                    <x-lucide-check class="w-4 h-4 mr-1" /> Approve
                                </button>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.admission.reward.reject', $reward->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-x class="w-4 h-4 mr-1" /> Reject
                                    </button>
                                </form>

                                <!-- Approve Modal -->
                                <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
                                        <h2 class="text-lg font-semibold mb-4">Approve Reward</h2>
                                        <form action="{{ route('admin.admission.reward.approve', $reward->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Reward Amount</label>
                                                <input type="number" name="reward" x-model="reward" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                            </div>
                                            <div class="flex justify-end gap-2">
                                                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Approve</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Modal -->
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        @if($rewards->count() > 0)
        <div class="mt-6">
            {{ $rewards->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
