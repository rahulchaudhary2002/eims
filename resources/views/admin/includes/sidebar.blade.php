{{--
    Sidebar - responds to Alpine.js $store.sidebar
    Classes applied by JS: sidebar-expanded / sidebar-collapsed / sidebar-icon-only / mobile-open
--}}
<aside id="app-sidebar" class="scrollbar">

    {{-- ── Logo ── --}}
    <div class="flex justify-center items-center h-[var(--header-height)] px-4 border-b border-white/10 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
            <img src="{{ asset('assets/images/logo.png') }}"
                 alt="{{ config('app.name','EIMS') }}"
                 class="h-[42px] w-auto shrink-0 object-contain">
        </a>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="overflow-y-auto overflow-x-hidden h-[calc(100%-var(--header-height))] pb-6 scrollbar">

        {{-- Dashboard --}}
        <div class="px-3 pt-4 pb-1">
            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-item rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                <span class="sidebar-label">Dashboard</span>
            </a>
        </div>

        {{-- ── Institution Management ── --}}
        @php $isInstitutionGroup = request()->routeIs('admin.institution.*') || request()->routeIs('admin.institution-type.*') || request()->routeIs('admin.institution-category.*'); @endphp
        <div class="px-3 pt-2">
            <p class="sidebar-section-label">Institution</p>

            <div x-data="sidebarGroup('institution', {{ $isInstitutionGroup ? 'true' : 'false' }})" class="relative">
                {{-- Collapsed: icon triggers dropright --}}
                <div class="sidebar-group-header rounded-lg {{ $isInstitutionGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                    <span class="sidebar-label flex-1">Institution Management</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>

                {{-- Accordion children --}}
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.institution.index') }}"
                       class="{{ request()->routeIs('admin.institution.*') ? 'active' : '' }}">Institutions</a>
                    <a href="{{ route('admin.institution-type.index') }}"
                       class="{{ request()->routeIs('admin.institution-type.*') ? 'active' : '' }}">Institution Types</a>
                    <a href="{{ route('admin.institution-category.index') }}"
                       class="{{ request()->routeIs('admin.institution-category.*') ? 'active' : '' }}">Institution Categories</a>
                </div>

                {{-- Dropright (icon-only mode) --}}
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()"
                         class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Institution</div>
                        <a href="{{ route('admin.institution.index') }}" class="{{ request()->routeIs('admin.institution.*') ? 'active' : '' }}">Institutions</a>
                        <a href="{{ route('admin.institution-type.index') }}" class="{{ request()->routeIs('admin.institution-type.*') ? 'active' : '' }}">Institution Types</a>
                        <a href="{{ route('admin.institution-category.index') }}" class="{{ request()->routeIs('admin.institution-category.*') ? 'active' : '' }}">Institution Categories</a>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Academic Management ── --}}
        @php $isAcademicGroup = request()->routeIs('admin.affiliation.*') || request()->routeIs('admin.level.*') || request()->routeIs('admin.program.*') || request()->routeIs('admin.course.*'); @endphp
        <div class="px-3 pt-2">
            <p class="sidebar-section-label">Academic</p>

            <div x-data="sidebarGroup('academic', {{ $isAcademicGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isAcademicGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                    <span class="sidebar-label flex-1">Academic Management</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.affiliation.index') }}" class="{{ request()->routeIs('admin.affiliation.*') ? 'active' : '' }}">Affiliations</a>
                    <a href="{{ route('admin.level.index') }}" class="{{ request()->routeIs('admin.level.*') ? 'active' : '' }}">Levels</a>
                    <a href="{{ route('admin.program.index') }}" class="{{ request()->routeIs('admin.program.*') ? 'active' : '' }}">Programs</a>
                    <a href="{{ route('admin.course.index') }}" class="{{ request()->routeIs('admin.course.*') ? 'active' : '' }}">Courses</a>
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Academic</div>
                        <a href="{{ route('admin.affiliation.index') }}" class="{{ request()->routeIs('admin.affiliation.*') ? 'active' : '' }}">Affiliations</a>
                        <a href="{{ route('admin.level.index') }}" class="{{ request()->routeIs('admin.level.*') ? 'active' : '' }}">Levels</a>
                        <a href="{{ route('admin.program.index') }}" class="{{ request()->routeIs('admin.program.*') ? 'active' : '' }}">Programs</a>
                        <a href="{{ route('admin.course.index') }}" class="{{ request()->routeIs('admin.course.*') ? 'active' : '' }}">Courses</a>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Scholarship Management ── --}}
        @php $isScholarshipGroup = request()->routeIs('admin.admission.reward.*'); @endphp
        <div class="px-3 pt-2">
            <p class="sidebar-section-label">Scholarship</p>

            <div x-data="sidebarGroup('scholarship', {{ $isScholarshipGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isScholarshipGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                    <span class="sidebar-label flex-1">Scholarship Mgmt</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.admission.reward.index') }}" class="{{ request()->routeIs('admin.admission.reward.*') ? 'active' : '' }}">Admission Rewards</a>
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Scholarship</div>
                        <a href="{{ route('admin.admission.reward.index') }}" class="{{ request()->routeIs('admin.admission.reward.*') ? 'active' : '' }}">Admission Rewards</a>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Referral & Commission ── --}}
        @php $isReferralGroup = request()->routeIs('admin.admission.commission.*') || request()->routeIs('admin.vendor.*'); @endphp
        <div class="px-3 pt-2">
            <p class="sidebar-section-label">Referral</p>

            <div x-data="sidebarGroup('referral', {{ $isReferralGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isReferralGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="sidebar-label flex-1">Referral & Commission</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.admission.commission.index') }}" class="{{ request()->routeIs('admin.admission.commission.*') ? 'active' : '' }}">Commission Invoices</a>
                    <a href="{{ route('admin.vendor.index') }}" class="{{ request()->routeIs('admin.vendor.*') ? 'active' : '' }}">Vendors / Agents</a>
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Referral & Commission</div>
                        <a href="{{ route('admin.admission.commission.index') }}" class="{{ request()->routeIs('admin.admission.commission.*') ? 'active' : '' }}">Commission Invoices</a>
                        <a href="{{ route('admin.vendor.index') }}" class="{{ request()->routeIs('admin.vendor.*') ? 'active' : '' }}">Vendors / Agents</a>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Settings ── --}}
        @php $isSettingsGroup = request()->routeIs('admin.bulk-import.*'); @endphp
        <div class="px-3 pt-2">
            <p class="sidebar-section-label">System</p>

            <div x-data="sidebarGroup('settings', {{ $isSettingsGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isSettingsGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="sidebar-label flex-1">Settings</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.bulk-import.index') }}" class="{{ request()->routeIs('admin.bulk-import.*') ? 'active' : '' }}">Bulk Import</a>
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Settings</div>
                        <a href="{{ route('admin.bulk-import.index') }}" class="{{ request()->routeIs('admin.bulk-import.*') ? 'active' : '' }}">Bulk Import</a>
                    </div>
                </template>
            </div>
        </div>

    </nav>
</aside>

