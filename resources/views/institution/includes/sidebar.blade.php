@php
    $groups = [
        'Dashboard' => [
            ['Dashboard', 'institution.dashboard', 'institution.dashboard'],
        ],
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
            ['Messages', 'institution.messages.index', 'institution.messages.*'],
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
        @foreach($groups as $group => $items)
            @php
                $groupKey = \Illuminate\Support\Str::slug($group);
                $isGroupActive = collect($items)->contains(fn ($item) => request()->routeIs($item[2]));
            @endphp
            <div class="px-3 pt-4">
                <p class="sidebar-section-label">{{ $group }}</p>
                <div x-data="sidebarGroup('{{ $groupKey }}', {{ $isGroupActive ? 'true' : 'false' }})" class="relative">
                    <div class="sidebar-group-header rounded-lg {{ $isGroupActive ? 'group-active' : '' }}" @click="isIconOnly() ? showDropright($event) : toggle()">
                        <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
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
