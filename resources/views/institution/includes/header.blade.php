@php
    $user = auth('web')->user();
    $activeInstitution = \App\Models\Institution::find(session('active_institution_id'));
    $switchable = $user?->is_super_admin
        ? \App\Models\Institution::active()->orderBy('name')->get()
        : $user?->activeInstitutions()->orderBy('institutions.name')->get();
@endphp

<header id="app-header" class="transition-all duration-300">
    <div class="flex items-center gap-3 flex-1 min-w-0">
        <button onclick="Alpine.store('sidebar').toggle()" class="w-9 h-9 flex items-center justify-center rounded-button border border-slate-200 bg-white text-slate-500 hover:bg-primary-50 hover:text-primary-600 hover:border-primary-200 transition-all duration-200 shrink-0" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
        </button>
        <div class="min-w-0">
            <h1 class="text-[15px] font-semibold text-slate-700 truncate hidden sm:block">@yield('page-title', 'Institution Dashboard')</h1>
            <p class="text-xs text-slate-400 truncate hidden md:block">{{ $activeInstitution?->name ?? 'Select institution' }}@if($activeInstitution) · {{ \App\Models\Institution::TYPES[$activeInstitution->type] ?? $activeInstitution->type }}@endif</p>
        </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        @if($user?->is_super_admin)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary h-9 py-0 text-sm">Admin Dashboard</a>
        @endif

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                class="flex items-center gap-2.5 px-2 py-1.5 rounded-button hover:bg-slate-50 transition-colors">
                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                    <span class="text-primary-700 text-sm font-bold">{{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $user?->name }}</p>
                    <p class="text-xs text-slate-400 leading-tight truncate max-w-[140px]">{{ $activeInstitution?->name ?? 'No institution' }}</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 hidden sm:block shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div x-show="open" x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="absolute top-12 right-0 w-72 bg-white rounded-card border border-slate-200 shadow-medium z-50 overflow-hidden">

                {{-- User info --}}
                <div class="px-4 py-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                            <span class="text-primary-700 text-sm font-bold">{{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $user?->name }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $user?->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Institution switcher --}}
                @if($switchable && $switchable->count() > 0)
                    <div class="border-b border-slate-100">
                        <p class="px-4 pt-3 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Institution</p>
                        <div class="max-h-48 overflow-y-auto">
                            @foreach($switchable as $institution)
                                @php $isActive = $activeInstitution?->id === $institution->id; @endphp
                                <form method="POST" action="{{ route('institution.select.store') }}">
                                    @csrf
                                    <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                                    <button type="submit" class="w-full flex items-center gap-3 pl-3 pr-4 py-2.5 text-sm transition-colors border-l-2 {{ $isActive ? 'bg-primary-50 border-primary-500 text-primary-700 font-medium' : 'border-transparent text-slate-600 hover:bg-slate-50' }}">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-full shrink-0 text-[11px] font-bold {{ $isActive ? 'bg-primary-600 text-white' : 'bg-slate-200 text-slate-600' }}">
                                            {{ strtoupper(substr($institution->name, 0, 1)) }}
                                        </span>
                                        <span class="truncate text-left flex-1">{{ $institution->name }}</span>
                                        @if($isActive)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Sign out --}}
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-danger hover:bg-danger-light transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
