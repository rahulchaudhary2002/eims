@php
    $groups = [
        'Institution Profile' => [
            ['Institution Details', 'institution.institutions.index', 'institution.institutions.*'],
            ['Profile', 'institution.profile.index', 'institution.profile.*'],
            ['Documents', 'institution.documents.index', 'institution.documents.*'],
            ['Programs', 'institution.programs.index', 'institution.programs.*'],
            ['Program Subjects', 'institution.program-subjects.index', 'institution.program-subjects.*'],
            ['Scholarships', 'institution.scholarships.index', 'institution.scholarships.*'],
        ],
        'Applications & Admissions' => [
            ['Applications', 'institution.applications.index', 'institution.applications.*'],
            ['Admissions', 'institution.admissions.index', 'institution.admissions.*'],
            ['Counseling Sessions', 'institution.counseling-sessions.index', 'institution.counseling-sessions.*'],
            ['Referrals', 'institution.referrals.index', 'institution.referrals.*'],
        ],
        'Lead & Inquiry' => [
            ['Inquiries', 'institution.inquiries.index', 'institution.inquiries.*'],
            ['Lead Notes', 'institution.lead-notes.index', 'institution.lead-notes.*'],
            ['Follow Ups', 'institution.lead-follow-ups.index', 'institution.lead-follow-ups.*'],
        ],
        'Content Management' => [
            ['Posts', 'institution.posts.index', 'institution.posts.*'],
            ['Post Media', 'institution.post-media.index', 'institution.post-media.*'],
            ['Reviews', 'institution.reviews.index', 'institution.reviews.*'],
        ],
        'Communication' => [
            ['Conversations', 'institution.conversations.index', 'institution.conversations.*'],
        ],
        'Promotion & Subscription' => [
            ['Promotions', 'institution.promotions.index', 'institution.promotions.*'],
            ['Subscriptions', 'institution.subscriptions.index', 'institution.subscriptions.*'],
        ],
        'Referral & Commission' => [
            ['Referrals', 'institution.referrals.index', 'institution.referrals.*'],
            ['Commission Invoices', 'institution.commission-invoices.index', 'institution.commission-invoices.*'],
            ['Commission Payments', 'institution.commission-payments.index', 'institution.commission-payments.*'],
        ],
    ];
@endphp

<aside id="app-sidebar" class="scrollbar">
    <div class="flex justify-center items-center h-[var(--header-height)] px-4 border-b border-white/10 shrink-0">
        <a href="{{ route('institution.dashboard') }}" class="flex items-center gap-3 min-w-0">
            <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name','EIMS') }}" class="h-[42px] w-auto shrink-0 object-contain">
        </a>
    </div>

    <nav class="overflow-y-auto overflow-x-hidden h-[calc(100%-var(--header-height))] pb-6 scrollbar">
        <div class="px-3 pt-4 pb-1">
            <a href="{{ route('institution.dashboard') }}"
                class="sidebar-item rounded-lg {{ request()->routeIs('institution.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                <span class="sidebar-label">Dashboard</span>
            </a>
        </div>

        @foreach($groups as $group => $items)
            @php
                $groupKey = \Illuminate\Support\Str::slug($group);
                $isGroupActive = collect($items)->contains(fn ($item) => request()->routeIs($item[2]));
            @endphp
            <div class="px-3 pt-4">
                <div x-data="sidebarGroup('{{ $groupKey }}', {{ $isGroupActive ? 'true' : 'false' }})" class="relative">
                    <div class="sidebar-group-header rounded-lg {{ $isGroupActive ? 'group-active' : '' }}" @click="isIconOnly() ? showDropright($event) : toggle()">
                        @switch($group)
                            @case('Institution Profile')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                                @break
                            @case('Applications & Admissions')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                                @break
                            @case('Lead & Inquiry')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm3.75 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm3.75 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 4.142-4.03 7.5-9 7.5a10.8 10.8 0 01-3.487-.57L3 21l1.7-4.25A7.006 7.006 0 013 12c0-4.142 4.03-7.5 9-7.5s9 3.358 9 7.5z"/></svg>
                                @break
                            @case('Content Management')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5A3.375 3.375 0 0010.125 2.25H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zM8.25 15h7.5M8.25 18h4.5"/></svg>
                                @break
                            @case('Communication')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                                @break
                            @case('Promotion & Subscription')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557L1.938 9.885a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345l2.125-5.111z"/></svg>
                                @break
                            @case('Referral & Commission')
                                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @break
                        @endswitch
                        <span class="sidebar-label flex-1">{{ $group }}</span>
                        <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    </div>
                    <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                        @foreach($items as [$label, $route, $active])
                            <a href="{{ route($route) }}" class="{{ request()->routeIs($active) ? 'active' : '' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                    <template x-teleport="body">
                        <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                            <div class="sidebar-dropright-title">{{ $group }}</div>
                            @foreach($items as [$label, $route, $active])
                                <a href="{{ route($route) }}" class="{{ request()->routeIs($active) ? 'active' : '' }}">{{ $label }}</a>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>
        @endforeach
    </nav>
</aside>
