@extends('vendor.layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-blue-600 text-3xl font-bold">{{ $courseCount }}</div>
        <div class="mt-2 text-gray-700 text-lg">Courses</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-red-600 text-3xl font-bold">Rs. {{ number_format($dueComission, 2) }}</div>
        <div class="mt-2 text-gray-700 text-lg">Due Commission</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 flex flex-col items-center">
        <div class="text-emerald-600 text-3xl font-bold">Rs. {{ number_format($paidComission, 2) }}</div>
        <div class="mt-2 text-gray-700 text-lg">Paid Commission</div>
    </div>
</div>
@endsection