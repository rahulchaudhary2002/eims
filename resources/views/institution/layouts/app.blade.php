<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EIMS') }} | Institution | @yield('title')</title>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/ckeditor-init.js', 'resources/js/select2-init.js'])
    @yield('page-specific-style')
</head>

<body class="bg-slate-50 antialiased">

    <!-- Mobile overlay -->
    <div id="sidebar-overlay" onclick="Alpine.store('sidebar').closeMobile()"></div>

    <!-- Sidebar -->
    @include('institution.includes.sidebar')

    <!-- Page Wrapper (header + main) -->
    <div id="page-wrapper">

        <!-- Header -->
        @include('institution.includes.header')

        <!-- Main Content -->
        <main class="px-4 sm:px-6 py-6 pt-[calc(var(--header-height)+24px)] min-h-screen">
            @yield('content')
        </main>

    </div>

    @yield('page-specific-script')
</body>

</html>
