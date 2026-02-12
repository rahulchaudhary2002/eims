@php
$user = auth()->guard('vendor')->user();
$currentInstitution = session('current_institution');
$vendorInstitutions = $user->institutions ?? collect(); // Assuming vendor has institutions relationship
@endphp

<div class="fixed border-b flex items-center justify-between h-[70px] left-[250px] w-[calc(100%-250px)] px-5 z-20 transition-all duration-300">
    <div class="flex items-center">
        <button id="toggleSidebar" class="hidden h-6 w-6">
            <x-lucide-chevron-left class="w-6 h-6" />
        </button>

        {{-- Institution Selector --}}
        @if($vendorInstitutions->count() > 0)
        <div class="ml-4 relative" id="institutionWrapper">
            <select id="institutionSelect" name="current_institution"
                class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option value="">Select Institution</option>
                @foreach($vendorInstitutions as $institution)
                <option value="{{ $institution->id }}"
                    {{ $currentInstitution && $currentInstitution->id == $institution->id ? 'selected' : '' }}>
                    {{ $institution->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <div class="flex items-center gap-5">
        {{-- Notifications --}}
        @php
        $vendor = auth('vendor')->user();
        $institutionId = session('current_institution')?->id;

        $notifications = $vendor
        ? $vendor->notifications()
        ->when($institutionId, fn ($q) =>
        $q->where('data->institution_id', $institutionId)
        )
        ->latest()
        ->take(5)
        ->get()
        : collect();
        @endphp
        <div class="relative" id="notificationWrapper">
            <button id="notificationBtn" class="relative">
                <x-lucide-bell class="w-6 h-6" />
                @if($notifications->whereNull('read_at')->count() > 0)
                <span class="absolute top-0 right-0 w-2 h-2 bg-red-600 rounded-full"></span>
                @endif
            </button>
            <div id="notificationDropdown" class="absolute hidden top-10 right-0 w-80 bg-white border rounded-md shadow-lg">
                <div class="flex items-center justify-between bg-gray-100 py-2 px-4 font-bold border-b">
                    <span>Notifications</span>
                    <form method="POST" action="{{ route('vendor.notification.read-all') }}">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline focus:outline-none">Mark all as read</button>
                    </form>
                </div>
                <ul class="max-h-80 overflow-y-auto divide-y">
                    @forelse($notifications as $notification)
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
                <a href="{{ route('vendor.institution.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Institution Profile</a>
                <form method="POST" action="{{ route('vendor.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Institution Selector
    const institutionSelect = document.getElementById('institutionSelect');
    if (institutionSelect) {
        institutionSelect.addEventListener('change', function() {
            const institutionId = this.value;

            // Send AJAX request to set current institution
            fetch('{{ route("vendor.set-current-institution") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        institution_id: institutionId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('Institution set successfully', 'success');
                        // Reload the page to reflect changes
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    } else {
                        showNotification('Failed to set institution', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred', 'error');
                });
        });
    }

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

    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' :
            type === 'error' ? 'bg-red-100 text-red-800 border border-red-300' :
            'bg-blue-100 text-blue-800 border border-blue-300'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <x-lucide-${type === 'success' ? 'check-circle' : type === 'error' ? 'alert-circle' : 'info'} class="w-5 h-5 mr-2" />
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 3000);
    }
</script>

<style>
    /* Custom select styling */
    #institutionSelect {
        min-width: 200px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    #institutionSelect:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    #institutionWrapper {
        position: relative;
    }

    #institutionWrapper .absolute {
        right: 0.75rem;
    }
</style>