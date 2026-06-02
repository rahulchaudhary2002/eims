<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta-title', config('app.name', 'EIMS'))</title>
    <meta name="description" content="@yield('meta-description', 'Find the best educational institutions, programs, and scholarships.')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('meta-title', config('app.name'))">
    <meta property="og:description" content="@yield('meta-description', '')">
    <meta property="og:type" content="website">
    @hasSection('og-image')
    <meta property="og:image" content="@yield('og-image')">
    @endif

    <link rel="icon" href="{{ asset('assets/images/icon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/select2-init.js'])
    @stack('styles')
</head>
<body class="legacy-sikuna bg-gray-50 font-sans">

    @include('includes.header')

    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('website.partials.footer')

    @stack('scripts')
</body>
</html>
