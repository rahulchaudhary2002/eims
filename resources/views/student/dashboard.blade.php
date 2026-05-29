@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<main class="flex-1 flex items-center justify-center py-10 px-4 mt-[80px]">
    <div class="container mx-auto max-w-4xl">
        <div class="bg-white rounded-2xl shadow p-10 text-center">
            <h1 class="text-3xl font-bold text-[#2c5aa0] mb-3">Welcome, {{ auth('student')->user()->name }}!</h1>
            <p class="text-gray-600 mb-6">Your student dashboard is coming soon.</p>
            <form method="POST" action="{{ route('student.logout') }}">
                @csrf
                <button type="submit" class="bg-[#4299e1] text-white px-6 py-2 rounded-lg hover:bg-[#2c5aa0] transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
