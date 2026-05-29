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
            <p class="text-xs text-slate-400 truncate hidden md:block">{{ $activeInstitution?->name ?? 'Select institution' }} @if($activeInstitution) · {{ \App\Models\Institution::TYPES[$activeInstitution->type] ?? $activeInstitution->type }} @endif</p>
        </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        @if($switchable && $switchable->count() > 1)
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-button border border-slate-200 bg-white text-slate-600 hover:bg-primary-50 hover:border-primary-200 text-sm max-w-[220px]">
                    <span class="truncate">{{ $activeInstitution?->name ?? 'Select institution' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" x-cloak class="absolute top-12 right-0 w-72 bg-white rounded-card border border-slate-200 shadow-medium z-50 overflow-hidden">
                    @foreach($switchable as $institution)
                        <form method="POST" action="{{ route('institution.select.store') }}">
                            @csrf
                            <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                            <button class="w-full text-left px-4 py-2.5 text-sm {{ $activeInstitution?->id === $institution->id ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">{{ $institution->name }}</button>
                        </form>
                    @endforeach
                </div>
            </div>
        @endif

        @if($user?->is_super_admin)
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary h-9 py-0 text-sm">Admin Dashboard</a>
        @endif

        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open" class="flex items-center gap-2.5 px-2 py-1.5 rounded-button hover:bg-slate-50 transition-colors">
                <div class="w-8 h-8 bg-primary-100 rounded-full overflow-hidden flex items-center justify-center shrink-0">
                    <span class="text-primary-700 text-sm font-bold">{{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}</span>
                </div>
                <span class="hidden sm:block text-sm font-semibold text-slate-700">{{ $user?->name }}</span>
            </button>
            <div x-show="open" x-cloak class="absolute top-12 right-0 w-52 bg-white rounded-card border border-slate-200 shadow-medium z-50 overflow-hidden">
                <div class="px-4 py-3 border-b border-slate-100">
                    <p class="text-sm font-semibold text-slate-700">{{ $user?->name }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $user?->email }}</p>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-danger hover:bg-danger-light transition-colors">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
