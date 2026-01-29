@extends('admin.layouts.app')
@section('title', 'Admission Commissions')

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">💰 Admission Commissions Management</h1>
            <p class="text-gray-600 mt-1">Manage admission commissions</p>
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
            @if($commissions->isEmpty())
            <p class="text-gray-500">There are no admission commissions yet.</p>
            @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Receipt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course/Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Institution</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Commission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($commissions as $index => $commission)
                        <tr x-data="{ open: false, commission: '{{ $commission->commission_amount ?? '' }}' }">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <a href="{{ asset('storage/' . $commission->admissionReward->admission_receipt) }}" target="_blank" class="text-indigo-600 hover:underline">View Receipt</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $commission->admissionReward->admissionApplication->course->name ?? $commission->admissionReward->admissionApplication->grade ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $commission->institution->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $commission->commission_amount ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $statusClasses = [
                                'false' => 'bg-yellow-100 text-yellow-800',
                                'true' => 'bg-green-100 text-green-800',
                                ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$commission->is_paid ? 'true' : 'false'] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $commission->is_paid ? 'Paid' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex gap-2">
                                @if(!$commission->is_paid)
                                <form method="POST" action="{{ route('admin.admission.commission.markPaid', $commission) }}">
                                    @csrf
                                    @method('PUT')
                                    <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-check class="w-4 h-4 mr-1" /> Mark as Paid
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        @if($commissions->count() > 0)
        <div class="mt-6">
            {{ $commissions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection