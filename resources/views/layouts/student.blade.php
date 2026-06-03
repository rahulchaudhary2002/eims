<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student') - {{ config('app.name', 'EIMS') }}</title>
    <meta name="description" content="Student Dashboard">

    <link rel="icon" href="{{ asset('assets/images/icon.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="legacy-sikuna bg-gray-50 font-sans">

    @php
        $studentPageTitle = trim($__env->yieldContent('title', 'Dashboard'));
        $studentCurrentLabel = strcasecmp($studentPageTitle, 'My Dashboard') === 0
            ? 'Dashboard'
            : $studentPageTitle;

        $studentBreadcrumbs = request()->routeIs('student.dashboard')
            ? [['label' => 'Dashboard']]
            : [
                ['label' => 'Dashboard', 'url' => route('student.dashboard')],
                ['label' => $studentCurrentLabel],
            ];
    @endphp

    @include('includes.header')

    <main class="min-h-screen relative">
        <div class="absolute inset-x-0 top-0 z-20 pointer-events-none pt-[105px]">
            <div class="container max-w-7xl mx-auto px-4">
                <div class="pointer-events-auto w-fit">
                    @include('website.partials.breadcrumb', [
                        'variant' => 'dark',
                        'breadcrumbs' => $studentBreadcrumbs,
                    ])
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>
</html>
