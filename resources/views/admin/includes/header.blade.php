@php
$user = auth()->guard('admin')->user();
@endphp

<div class="fixed border-b flex items-center justify-between h-[70px] left-[250px] w-[calc(100%-250px)] px-5 z-20 transition-all duration-300">
    <div class="flex items-center">
        <button id="toggleSidebar" class="hidden h-6 w-6">
            <x-lucide-chevron-left class="w-6 h-6" />
        </button>
    </div>

    <div class="flex items-center gap-5">
        {{-- Notifications --}}
        <div class="relative" id="notificationWrapper">
            <button id="notificationBtn" class="relative">
                <x-lucide-bell class="w-6 h-6" />
                @if(auth('admin')->user()->unreadNotifications->count() > 0)
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                @endif
            </button>
            <div id="notificationDropdown" class="absolute hidden top-10 right-0 w-80 bg-white border rounded-md shadow-lg">
                <div class="flex items-center justify-between bg-gray-100 py-2 px-4 font-bold border-b">
                    <span>Notifications</span>
                    <form method="POST" action="{{ route('admin.notification.read-all') }}">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline focus:outline-none">Mark all as read</button>
                    </form>
                </div>
                <ul class="max-h-80 overflow-y-auto divide-y">
                    @forelse(auth('admin')->user()->notifications()->latest()->take(5)->get() as $notification)
                    <li class="px-4 py-3 hover:bg-gray-50 flex items-start gap-2 {{ $notification->read_at ? 'opacity-60' : '' }}">
                        <div class="mt-1">
                            @if($notification->read_at)
                            <x-lucide-circle class="w-3 h-3 text-gray-300" />
                            @else
                            <x-lucide-dot class="w-3 h-3 text-blue-500" />
                            @endif
                        </div>

                        <div>
                            <div class="text-sm">
                                {{ $notification->data['message'] ?? 'Notification' }}
                            </div>

                            <div class="text-xs text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-6 text-center text-gray-400">
                        No notifications
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Profile --}}
        <div class="relative" id="profileWrapper">
            <button id="profileBtn" class="flex items-center gap-2">
                <div class="w-9 h-9 bg-gray-200 rounded-full overflow-hidden">
                    <img src="{{ asset('images/logo.png') }}" alt="Profile">
                </div>
                <div>
                    <p class="text-sm font-semibold">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">Admin</p>
                </div>
            </button>
            <div id="profileDropdown" class="absolute hidden top-12 right-0 w-52 bg-white border rounded-md shadow-lg">
                <a href="{{-- route('profile') --}}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle Sidebar
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        document.body.classList.toggle('sidebar-closed');
    });

    // Notifications Dropdown
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    notificationBtn.addEventListener('click', () => {
        notificationDropdown.classList.toggle('hidden');
    });

    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    profileBtn.addEventListener('click', () => {
        profileDropdown.classList.toggle('hidden');
    });

    // Close on click outside
    document.addEventListener('click', function(e) {
        if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
            notificationDropdown.classList.add('hidden');
        }
        if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>