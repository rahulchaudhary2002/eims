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

        {{-- ── User Management ── --}}
        @php $isUserGroup = request()->routeIs('admin.users.*') || request()->routeIs('admin.students.*') || request()->routeIs('admin.student-profiles.*') || request()->routeIs('admin.student-academic-records.*') || request()->routeIs('admin.student-documents.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('users', {{ $isUserGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isUserGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    <span class="sidebar-label flex-1">User Management</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>

                {{-- Accordion children --}}
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    <a href="{{ route('admin.students.index') }}"
                       class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">Students</a>
                    <a href="{{ route('admin.student-profiles.index') }}"
                       class="{{ request()->routeIs('admin.student-profiles.*') ? 'active' : '' }}">Student Profiles</a>
                    <a href="{{ route('admin.student-academic-records.index') }}"
                       class="{{ request()->routeIs('admin.student-academic-records.*') ? 'active' : '' }}">Academic Records</a>
                    <a href="{{ route('admin.student-documents.index') }}"
                       class="{{ request()->routeIs('admin.student-documents.*') ? 'active' : '' }}">Student Documents</a>
                </div>

                {{-- Dropright (icon-only mode) --}}
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()"
                         class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Users</div>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                        <a href="{{ route('admin.students.index') }}" class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">Students</a>
                        <a href="{{ route('admin.student-profiles.index') }}" class="{{ request()->routeIs('admin.student-profiles.*') ? 'active' : '' }}">Student Profiles</a>
                        <a href="{{ route('admin.student-academic-records.index') }}" class="{{ request()->routeIs('admin.student-academic-records.*') ? 'active' : '' }}">Academic Records</a>
                        <a href="{{ route('admin.student-documents.index') }}" class="{{ request()->routeIs('admin.student-documents.*') ? 'active' : '' }}">Student Documents</a>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Institution Management ── --}}
        @php
            $webUser = auth('web')->user();
            $canViewProgramSubjects = $webUser && ($webUser->is_super_admin || session('current_institution_id'));
            $isInstitutionGroup = request()->routeIs('admin.institutions.*') || request()->routeIs('admin.institution-profiles.*') || request()->routeIs('admin.institution-documents.*') || request()->routeIs('admin.institution-programs.*') || request()->routeIs('admin.institution-program-subjects.*') || request()->routeIs('admin.institution-courses.*') || request()->routeIs('admin.institution-certifications.*') || request()->routeIs('admin.institution-followers.*') || request()->routeIs('admin.institution-reviews.*');
        @endphp
        <div class="px-3 pt-2">
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
                    <a href="{{ route('admin.institutions.index') }}"
                       class="{{ request()->routeIs('admin.institutions.*') ? 'active' : '' }}">Institutions</a>
                    <a href="{{ route('admin.institution-profiles.index') }}"
                       class="{{ request()->routeIs('admin.institution-profiles.*') ? 'active' : '' }}">Institution Profiles</a>
                    <a href="{{ route('admin.institution-documents.index') }}"
                       class="{{ request()->routeIs('admin.institution-documents.*') ? 'active' : '' }}">Institution Documents</a>
                    <a href="{{ route('admin.institution-programs.index') }}"
                       class="{{ request()->routeIs('admin.institution-programs.*') ? 'active' : '' }}">Institution Programs</a>
                    @if($canViewProgramSubjects)
                        <a href="{{ route('admin.institution-program-subjects.index') }}"
                           class="{{ request()->routeIs('admin.institution-program-subjects.*') ? 'active' : '' }}">Program Subjects</a>
                    @endif
                    <a href="{{ route('admin.institution-courses.index') }}"
                       class="{{ request()->routeIs('admin.institution-courses.*') ? 'active' : '' }}">Institution Courses</a>
                    <a href="{{ route('admin.institution-certifications.index') }}"
                       class="{{ request()->routeIs('admin.institution-certifications.*') ? 'active' : '' }}">Institution Certifications</a>
                    @auth('web')
                        <a href="{{ route('admin.institution-followers.index') }}"
                           class="{{ request()->routeIs('admin.institution-followers.*') ? 'active' : '' }}">Institution Followers</a>
                        <a href="{{ route('admin.institution-reviews.index') }}"
                           class="{{ request()->routeIs('admin.institution-reviews.*') ? 'active' : '' }}">Institution Reviews</a>
                    @endauth
                </div>

                {{-- Dropright (icon-only mode) --}}
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()"
                         class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Institution Management</div>
                        <a href="{{ route('admin.institutions.index') }}" class="{{ request()->routeIs('admin.institutions.*') ? 'active' : '' }}">Institutions</a>
                        <a href="{{ route('admin.institution-profiles.index') }}" class="{{ request()->routeIs('admin.institution-profiles.*') ? 'active' : '' }}">Institution Profiles</a>
                        <a href="{{ route('admin.institution-documents.index') }}" class="{{ request()->routeIs('admin.institution-documents.*') ? 'active' : '' }}">Institution Documents</a>
                        <a href="{{ route('admin.institution-programs.index') }}" class="{{ request()->routeIs('admin.institution-programs.*') ? 'active' : '' }}">Institution Programs</a>
                        @if($canViewProgramSubjects)
                            <a href="{{ route('admin.institution-program-subjects.index') }}" class="{{ request()->routeIs('admin.institution-program-subjects.*') ? 'active' : '' }}">Program Subjects</a>
                        @endif
                        <a href="{{ route('admin.institution-courses.index') }}" class="{{ request()->routeIs('admin.institution-courses.*') ? 'active' : '' }}">Institution Courses</a>
                        <a href="{{ route('admin.institution-certifications.index') }}" class="{{ request()->routeIs('admin.institution-certifications.*') ? 'active' : '' }}">Institution Certifications</a>
                        @auth('web')
                            <a href="{{ route('admin.institution-followers.index') }}" class="{{ request()->routeIs('admin.institution-followers.*') ? 'active' : '' }}">Institution Followers</a>
                            <a href="{{ route('admin.institution-reviews.index') }}" class="{{ request()->routeIs('admin.institution-reviews.*') ? 'active' : '' }}">Institution Reviews</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Academic Setup ── --}}
        @php $isAcademicSetupGroup = request()->routeIs('admin.faculties.*') || request()->routeIs('admin.programs.*') || request()->routeIs('admin.scholarships.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('academic-setup', {{ $isAcademicSetupGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isAcademicSetupGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    <span class="sidebar-label flex-1">Academic Setup</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    <a href="{{ route('admin.faculties.index') }}"
                       class="{{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}">Faculties</a>
                    <a href="{{ route('admin.programs.index') }}"
                       class="{{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">Programs</a>
                    @auth('web')
                        <a href="{{ route('admin.scholarships.index') }}"
                           class="{{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}">Scholarships</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()"
                         class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Academic Setup</div>
                        <a href="{{ route('admin.faculties.index') }}" class="{{ request()->routeIs('admin.faculties.*') ? 'active' : '' }}">Faculties</a>
                        <a href="{{ route('admin.programs.index') }}" class="{{ request()->routeIs('admin.programs.*') ? 'active' : '' }}">Programs</a>
                        @auth('web')
                            <a href="{{ route('admin.scholarships.index') }}" class="{{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}">Scholarships</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Applications & Admissions ── --}}
        @php $isApplicationsGroup = request()->routeIs('admin.applications.*') || request()->routeIs('admin.admissions.*') || request()->routeIs('admin.application-status-logs.*') || request()->routeIs('admin.scholarship-applications.*') || request()->routeIs('admin.scholarship-cashbacks.*') || request()->routeIs('admin.student-reward-claims.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('applications-admissions', {{ $isApplicationsGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isApplicationsGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25 4.5H6.75A2.25 2.25 0 014.5 18.25V5.75A2.25 2.25 0 016.75 3.5h7.19c.597 0 1.169.237 1.591.659l2.81 2.81c.422.422.659.994.659 1.591v9.69a2.25 2.25 0 01-2.25 2.25z"/></svg>
                    <span class="sidebar-label flex-1">Applications & Admissions</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.applications.index') }}"
                           class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">Applications</a>
                        <a href="{{ route('admin.admissions.index') }}"
                           class="{{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}">Admissions</a>
                        <a href="{{ route('admin.application-status-logs.index') }}"
                           class="{{ request()->routeIs('admin.application-status-logs.*') ? 'active' : '' }}">Application Status Logs</a>
                        <a href="{{ route('admin.scholarship-applications.index') }}"
                           class="{{ request()->routeIs('admin.scholarship-applications.*') ? 'active' : '' }}">Scholarship Applications</a>
                        <a href="{{ route('admin.scholarship-cashbacks.index') }}"
                           class="{{ request()->routeIs('admin.scholarship-cashbacks.*') ? 'active' : '' }}">Scholarship Cashbacks</a>
                        <a href="{{ route('admin.student-reward-claims.index') }}"
                           class="{{ request()->routeIs('admin.student-reward-claims.*') ? 'active' : '' }}">Reward Claims</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Applications & Admissions</div>
                        @auth('web')
                            <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">Applications</a>
                            <a href="{{ route('admin.admissions.index') }}" class="{{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}">Admissions</a>
                            <a href="{{ route('admin.application-status-logs.index') }}" class="{{ request()->routeIs('admin.application-status-logs.*') ? 'active' : '' }}">Application Status Logs</a>
                            <a href="{{ route('admin.scholarship-applications.index') }}" class="{{ request()->routeIs('admin.scholarship-applications.*') ? 'active' : '' }}">Scholarship Applications</a>
                            <a href="{{ route('admin.scholarship-cashbacks.index') }}" class="{{ request()->routeIs('admin.scholarship-cashbacks.*') ? 'active' : '' }}">Scholarship Cashbacks</a>
                            <a href="{{ route('admin.student-reward-claims.index') }}" class="{{ request()->routeIs('admin.student-reward-claims.*') ? 'active' : '' }}">Reward Claims</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Referral & Commission ── --}}
        @php $isReferralGroup = request()->routeIs('admin.referral-agreements.*') || request()->routeIs('admin.referrals.*') || request()->routeIs('admin.commission-invoices.*') || request()->routeIs('admin.commission-payments.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('referral', {{ $isReferralGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isReferralGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="sidebar-label flex-1">Referral & Commission</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.referral-agreements.index') }}" class="{{ request()->routeIs('admin.referral-agreements.*') ? 'active' : '' }}">Referral Agreements</a>
                        <a href="{{ route('admin.referrals.index') }}" class="{{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}">Referrals</a>
                        <a href="{{ route('admin.commission-invoices.index') }}" class="{{ request()->routeIs('admin.commission-invoices.*') ? 'active' : '' }}">Commission Invoices</a>
                        <a href="{{ route('admin.commission-payments.index') }}" class="{{ request()->routeIs('admin.commission-payments.*') ? 'active' : '' }}">Commission Payments</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Referral & Commission</div>
                    @auth('web')
                        <a href="{{ route('admin.referral-agreements.index') }}" class="{{ request()->routeIs('admin.referral-agreements.*') ? 'active' : '' }}">Referral Agreements</a>
                            <a href="{{ route('admin.referrals.index') }}" class="{{ request()->routeIs('admin.referrals.*') ? 'active' : '' }}">Referrals</a>
                            <a href="{{ route('admin.commission-invoices.index') }}" class="{{ request()->routeIs('admin.commission-invoices.*') ? 'active' : '' }}">Commission Invoices</a>
                            <a href="{{ route('admin.commission-payments.index') }}" class="{{ request()->routeIs('admin.commission-payments.*') ? 'active' : '' }}">Commission Payments</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Lead & Inquiry ── --}}
        @php $isLeadGroup = request()->routeIs('admin.inquiries.*') || request()->routeIs('admin.lead-notes.*') || request()->routeIs('admin.lead-follow-ups.*') || request()->routeIs('admin.counseling-sessions.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('lead-inquiry', {{ $isLeadGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isLeadGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                    <span class="sidebar-label flex-1">Lead & Inquiry</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.inquiries.index') }}" class="{{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">Inquiries</a>
                        <a href="{{ route('admin.lead-notes.index') }}" class="{{ request()->routeIs('admin.lead-notes.*') ? 'active' : '' }}">Lead Notes</a>
                        <a href="{{ route('admin.lead-follow-ups.index') }}" class="{{ request()->routeIs('admin.lead-follow-ups.*') ? 'active' : '' }}">Lead Follow Ups</a>
                        <a href="{{ route('admin.counseling-sessions.index') }}" class="{{ request()->routeIs('admin.counseling-sessions.*') ? 'active' : '' }}">Counseling Sessions</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Lead & Inquiry</div>
                        @auth('web')
                            <a href="{{ route('admin.inquiries.index') }}" class="{{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">Inquiries</a>
                            <a href="{{ route('admin.lead-notes.index') }}" class="{{ request()->routeIs('admin.lead-notes.*') ? 'active' : '' }}">Lead Notes</a>
                            <a href="{{ route('admin.lead-follow-ups.index') }}" class="{{ request()->routeIs('admin.lead-follow-ups.*') ? 'active' : '' }}">Lead Follow Ups</a>
                            <a href="{{ route('admin.counseling-sessions.index') }}" class="{{ request()->routeIs('admin.counseling-sessions.*') ? 'active' : '' }}">Counseling Sessions</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Student Activity ── --}}
        @php $isStudentActivityGroup = request()->routeIs('admin.student-favorite-institutions.*') || request()->routeIs('admin.student-compare-items.*') || request()->routeIs('admin.student-recommendations.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('student-activity', {{ $isStudentActivityGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isStudentActivityGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                    <span class="sidebar-label flex-1">Student Activity</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.student-favorite-institutions.index') }}" class="{{ request()->routeIs('admin.student-favorite-institutions.*') ? 'active' : '' }}">Favorite Institutions</a>
                        <a href="{{ route('admin.student-compare-items.index') }}" class="{{ request()->routeIs('admin.student-compare-items.*') ? 'active' : '' }}">Compare Items</a>
                        <a href="{{ route('admin.student-recommendations.index') }}" class="{{ request()->routeIs('admin.student-recommendations.*') ? 'active' : '' }}">Recommendations</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Student Activity</div>
                        @auth('web')
                            <a href="{{ route('admin.student-favorite-institutions.index') }}" class="{{ request()->routeIs('admin.student-favorite-institutions.*') ? 'active' : '' }}">Favorite Institutions</a>
                            <a href="{{ route('admin.student-compare-items.index') }}" class="{{ request()->routeIs('admin.student-compare-items.*') ? 'active' : '' }}">Compare Items</a>
                            <a href="{{ route('admin.student-recommendations.index') }}" class="{{ request()->routeIs('admin.student-recommendations.*') ? 'active' : '' }}">Recommendations</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Content Management ── --}}
        @php $isContentGroup = request()->routeIs('admin.posts.*') || request()->routeIs('admin.post-media.*') || request()->routeIs('admin.post-reactions.*') || request()->routeIs('admin.post-comments.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('content', {{ $isContentGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isContentGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"/></svg>
                    <span class="sidebar-label flex-1">Content Management</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">Posts</a>
                        <a href="{{ route('admin.post-media.index') }}" class="{{ request()->routeIs('admin.post-media.*') ? 'active' : '' }}">Post Media</a>
                        <a href="{{ route('admin.post-reactions.index') }}" class="{{ request()->routeIs('admin.post-reactions.*') ? 'active' : '' }}">Post Reactions</a>
                        <a href="{{ route('admin.post-comments.index') }}" class="{{ request()->routeIs('admin.post-comments.*') ? 'active' : '' }}">Post Comments</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Content Management</div>
                        @auth('web')
                            <a href="{{ route('admin.posts.index') }}" class="{{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">Posts</a>
                            <a href="{{ route('admin.post-media.index') }}" class="{{ request()->routeIs('admin.post-media.*') ? 'active' : '' }}">Post Media</a>
                            <a href="{{ route('admin.post-reactions.index') }}" class="{{ request()->routeIs('admin.post-reactions.*') ? 'active' : '' }}">Post Reactions</a>
                            <a href="{{ route('admin.post-comments.index') }}" class="{{ request()->routeIs('admin.post-comments.*') ? 'active' : '' }}">Post Comments</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Consultancy ── --}}
        @php $isConsultancyGroup = request()->routeIs('admin.consultancy-destinations.*') || request()->routeIs('admin.consultancy-services.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('consultancy', {{ $isConsultancyGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isConsultancyGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                    <span class="sidebar-label flex-1">Consultancy</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.consultancy-destinations.index') }}" class="{{ request()->routeIs('admin.consultancy-destinations.*') ? 'active' : '' }}">Consultancy Destinations</a>
                        <a href="{{ route('admin.consultancy-services.index') }}" class="{{ request()->routeIs('admin.consultancy-services.*') ? 'active' : '' }}">Consultancy Services</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Consultancy</div>
                        @auth('web')
                            <a href="{{ route('admin.consultancy-destinations.index') }}" class="{{ request()->routeIs('admin.consultancy-destinations.*') ? 'active' : '' }}">Consultancy Destinations</a>
                            <a href="{{ route('admin.consultancy-services.index') }}" class="{{ request()->routeIs('admin.consultancy-services.*') ? 'active' : '' }}">Consultancy Services</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Subscription & Promotion ── --}}
        @php $isSubscriptionGroup = request()->routeIs('admin.subscription-plans.*') || request()->routeIs('admin.institution-subscriptions.*') || request()->routeIs('admin.promotions.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('subscription', {{ $isSubscriptionGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isSubscriptionGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                    <span class="sidebar-label flex-1">Subscription & Promotion</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.subscription-plans.index') }}" class="{{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">Subscription Plans</a>
                        <a href="{{ route('admin.institution-subscriptions.index') }}" class="{{ request()->routeIs('admin.institution-subscriptions.*') ? 'active' : '' }}">Institution Subscriptions</a>
                        <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">Promotions</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Subscription & Promotion</div>
                        @auth('web')
                            <a href="{{ route('admin.subscription-plans.index') }}" class="{{ request()->routeIs('admin.subscription-plans.*') ? 'active' : '' }}">Subscription Plans</a>
                            <a href="{{ route('admin.institution-subscriptions.index') }}" class="{{ request()->routeIs('admin.institution-subscriptions.*') ? 'active' : '' }}">Institution Subscriptions</a>
                            <a href="{{ route('admin.promotions.index') }}" class="{{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">Promotions</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>

        {{-- ── Communication ── --}}
        @php $isCommunicationGroup = request()->routeIs('admin.conversations.*'); @endphp
        <div class="px-3 pt-2">
            <div x-data="sidebarGroup('communication', {{ $isCommunicationGroup ? 'true' : 'false' }})" class="relative">
                <div class="sidebar-group-header rounded-lg {{ $isCommunicationGroup ? 'group-active' : '' }}"
                     @click="isIconOnly() ? showDropright($event) : toggle()">
                    <svg class="sidebar-item-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                    <span class="sidebar-label flex-1">Communication</span>
                    <svg class="sidebar-chevron sidebar-label" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" :class="open ? 'rotate-180' : ''"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </div>
                <div class="sidebar-children" x-show="open && !isIconOnly()" x-collapse>
                    @auth('web')
                        <a href="{{ route('admin.conversations.index') }}" class="{{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">Conversations</a>
                    @endauth
                </div>
                <template x-teleport="body">
                    <div x-show="droprightOpen" x-cloak @click.outside="hideDropright()" class="sidebar-dropright" :style="`top:${droprightTop}px;left:${droprightLeft}px`">
                        <div class="sidebar-dropright-title">Communication</div>
                        @auth('web')
                            <a href="{{ route('admin.conversations.index') }}" class="{{ request()->routeIs('admin.conversations.*') ? 'active' : '' }}">Conversations</a>
                        @endauth
                    </div>
                </template>
            </div>
        </div>


    </nav>
</aside>
