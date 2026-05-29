@php
$currentRoute = request()->route()?->getName();
@endphp

<aside class="sticky top-20 lg:col-span-1 bg-white shadow rounded-2xl p-6 flex flex-col lg:space-y-4 max-h-[calc(100vh-80px)] mb-4 overflow-auto z-20">
    <h2 class="hidden text-xl font-semibold text-gray-800 lg:block">Account Settings</h2>
    <nav class="flex lg:flex-col max-lg:items-center lg:space-y-2">
        <a href="{{ route('profile.edit') }}"
            class="px-4 py-2 rounded-lg font-medium transition
           {{ $currentRoute === 'profile.edit' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
            Profile
        </a>
    </nav>
</aside>
