<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EIMS') }} | @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-specific-style')
</head>

<body class="min-h-screen min-w-screen bg-gray-50 overflow-hidden relative">

    @include('vendor.includes.sidebar')

    @include('vendor.includes.header')

    @php
    $sidebarOpen = session('sidebarOpen', true);
    @endphp

    <main
        class="relative top-[70px] max-md:left-0 max-md:w-full px-2 sm:px-5 py-3
        transition-all duration-300 h-[calc(100vh-70px)] overflow-y-auto
        {{ $sidebarOpen
            ? 'left-[250px] w-[calc(100%-250px)] max-lg:w-[calc(100%-70px)] max-lg:left-[70px]'
            : 'left-[70px] w-[calc(100%-70px)]'
        }}">
        @yield('content')
    </main>

    <script src="https://cdn.tiny.cloud/1/8wbt89rzkyg60acmtvlic31msdvwo1jbftv5sfl6ws93wevi/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

    @yield('page-specific-script')
</body>

</html>