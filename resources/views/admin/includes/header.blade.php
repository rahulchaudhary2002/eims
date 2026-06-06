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
        @php $unreadCount = auth('web')->user()->unreadNotifications->count(); @endphp
        <div class="relative" x-data="{
                open: false,
                unread: {{ $unreadCount }},
                items: [],
                init() {
                    if (typeof window.Echo === 'undefined') return;
                    window.Echo.private('platform.notifications')
                        .listen('.new.registration', (e) => {
                            this.unread++;
                            this.items.unshift({
                                message: e.message,
                                time: e.time,
                                url: e.url || null,
                            });
                            this.showToast(e.message, e.url);
                        });
                },
                showToast(msg, url) {
                    const t = document.createElement('a');
                    t.href = url || '#';
                    t.className = 'fixed bottom-5 right-5 z-[9999] bg-slate-800 text-white text-sm px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 hover:bg-slate-700 transition-all no-underline cursor-pointer';
                    t.innerHTML = `<span class='w-2 h-2 rounded-full bg-green-400 shrink-0'></span><span>${msg}</span><span class='text-xs text-slate-400 shrink-0'>View →</span>`;
                    document.body.appendChild(t);
                    setTimeout(() => t.remove(), 5000);
                }
            }"
            @click.outside="open = false">
            <button @click="open = !open"
                class="relative w-9 h-9 flex items-center justify-center rounded-button border border-slate-200 bg-white text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                </svg>
                <span x-show="unread > 0" class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full ring-2 ring-white"></span>
            </button>

            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="absolute top-12 right-0 w-80 bg-white rounded-card border border-slate-200 shadow-medium z-50">
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <span class="text-sm font-semibold text-slate-700">
                        Notifications
                        <span x-show="unread > 0" x-text="'(' + unread + ')'" class="text-primary-600 ml-1"></span>
                    </span>
                    <form method="POST" action="{{ route('admin.notification.read-all') }}">
                        @csrf
                        <button type="submit" @click="unread = 0" class="text-xs text-primary-600 hover:underline font-medium">
                            Mark all read
                        </button>
                    </form>
                </div>
                <ul class="max-h-80 overflow-y-auto divide-y divide-slate-50" id="notification-list">
                    {{-- Real-time items prepended by Alpine --}}
                    <template x-for="(item, i) in items" :key="i">
                        <li class="hover:bg-slate-50 transition-colors">
                            <a :href="item.url || '#'" class="px-4 py-3 flex items-start gap-3 no-underline">
                                <div class="mt-1.5 shrink-0">
                                    <span class="block w-2 h-2 rounded-full bg-primary-500"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-slate-700 leading-snug" x-text="item.message"></p>
                                    <p class="text-xs text-slate-400 mt-1" x-text="item.time"></p>
                                </div>
                            </a>
                        </li>
                    </template>
                    @forelse(auth('web')->user()->notifications()->latest()->take(8)->get() as $notification)
                    @php $notifUrl = $notification->data['url'] ?? null; @endphp
                    <li class="hover:bg-slate-50 transition-colors {{ $notification->read_at ? 'opacity-60' : '' }}">
                        @if($notifUrl)
                        <a href="{{ $notifUrl }}" class="px-4 py-3 flex items-start gap-3 no-underline">
                        @else
                        <div class="px-4 py-3 flex items-start gap-3">
                        @endif
                            <div class="mt-1.5 shrink-0">
                                <span class="block w-2 h-2 rounded-full {{ $notification->read_at ? 'bg-slate-300' : 'bg-primary-500' }}"></span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm text-slate-700 leading-snug">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        @if($notifUrl)
                        </a>
                        @else
                        </div>
                        @endif
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-slate-400 text-sm" x-show="items.length === 0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.143 17.082a24.248 24.248 0 003.844.148m-3.844-.148a23.856 23.856 0 01-5.455-1.31 8.964 8.964 0 002.3-5.542m3.155 6.852a3 3 0 005.667 1.097m1.765-6.565a4.5 4.5 0 00-1.765-3.346M3 3l18 18"/>
                        </svg>
                        No notifications
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

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

