@php $user = auth('web')->user(); @endphp

<header id="app-header" class="transition-all duration-300">

    {{-- ── Left: Sidebar Toggle + Breadcrumb ── --}}
    <div class="flex items-center gap-3 flex-1 min-w-0">
        <button
            onclick="Alpine.store('sidebar').toggle()"
            class="w-9 h-9 flex items-center justify-center rounded-button border border-slate-200 bg-white text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all duration-200 shrink-0"
            aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        {{-- Page title slot or breadcrumb --}}
        <div class="min-w-0">
            <h1 class="text-[15px] font-semibold text-slate-700 truncate hidden sm:block">
                @yield('page-title', config('app.name', 'EIMS'))
            </h1>
        </div>
    </div>

    {{-- ── Right: Notifications + Profile ── --}}
    <div class="flex items-center gap-2 shrink-0">

        {{-- Notifications --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="relative w-9 h-9 flex items-center justify-center rounded-button border border-slate-200 bg-white text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                </svg>
                @if(auth('web')->user()->unreadNotifications->count() > 0)
                <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full ring-2 ring-white"></span>
                @endif
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute top-12 right-0 w-80 bg-white rounded-card border border-slate-200 shadow-medium z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-semibold text-slate-700">Notifications</span>
                    <form method="POST" action="{{ route('admin.notification.read-all') }}">
                        @csrf
                        <button type="submit" class="text-xs text-primary-600 hover:underline font-medium">
                            Mark all read
                        </button>
                    </form>
                </div>
                <ul class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                    @forelse(auth('web')->user()->notifications()->latest()->take(8)->get() as $notification)
                    <li class="px-4 py-3 hover:bg-slate-50 flex items-start gap-3 transition-colors {{ $notification->read_at ? 'opacity-60' : '' }}">
                        <div class="mt-1.5 shrink-0">
                            @if($notification->read_at)
                            <span class="block w-2 h-2 rounded-full bg-slate-300"></span>
                            @else
                            <span class="block w-2 h-2 rounded-full bg-primary-500"></span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-slate-700 leading-snug">{{ $notification->data['message'] ?? 'Notification' }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-slate-400 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 003.844.148m-3.844-.148a23.856 23.856 0 01-5.455-1.31 8.964 8.964 0 002.3-5.542m3.155 6.852a3 3 0 005.667 1.097m1.765-6.565a4.5 4.5 0 00-1.765-3.346M3 3l18 18"/>
                        </svg>
                        No notifications
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Institution Switcher --}}
        @php
            $switchable = $user->is_super_admin
                ? \App\Models\Institution::where('status', 'active')->orderBy('name')->get(['id', 'name'])
                : $user->activeInstitutions()->orderBy('name')->get(['id', 'name']);
            $currentInstId = session('current_institution_id');
            $currentInst   = $switchable->firstWhere('id', $currentInstId);
            $showSwitcher  = $user->is_super_admin ? $switchable->isNotEmpty() : $switchable->count() > 1;
        @endphp

        @if($showSwitcher)
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-button border border-slate-200 bg-white text-slate-600 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-700 transition-all duration-200 text-sm max-w-[180px]"
                title="Switch institution">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                </svg>
                <span class="truncate hidden sm:block">{{ $currentInst?->name ?? 'Select institution' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                </svg>
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute top-12 right-0 w-64 bg-white rounded-card border border-slate-200 shadow-medium z-50 overflow-hidden">
                <div class="px-4 py-2.5 border-b border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Switch Institution</p>
                </div>
                <ul class="max-h-72 overflow-y-auto py-1">
                    @foreach($switchable as $inst)
                    <li>
                        <form method="POST" action="{{ route('admin.institutions.switch-current') }}">
                            @csrf
                            <input type="hidden" name="institution_id" value="{{ $inst->id }}">
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 text-sm transition-colors
                                    {{ $currentInstId == $inst->id
                                        ? 'bg-indigo-50 text-indigo-700 font-semibold'
                                        : 'text-slate-600 hover:bg-slate-50' }}">
                                <span class="flex-1 text-left truncate">{{ $inst->name }}</span>
                                @if($currentInstId == $inst->id)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" clip-rule="evenodd"/>
                                </svg>
                                @endif
                            </button>
                        </form>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Profile --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-2.5 px-2 py-1.5 rounded-button hover:bg-slate-50 transition-colors">
                <div class="w-8 h-8 bg-primary-100 rounded-full overflow-hidden flex items-center justify-center shrink-0">
                    <span class="text-primary-700 text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 leading-tight">Administrator</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute top-12 right-0 w-52 bg-white rounded-card border border-slate-200 shadow-medium z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-700">{{ $user->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</p>
                </div>
                <a href="#" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Profile Settings
                </a>
                @if($user->activeInstitutions()->exists())
                    <a href="{{ route('institution.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 transition-colors">
                        Institution Dashboard
                    </a>
                @endif
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-danger hover:bg-danger-light transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>

</header>


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
