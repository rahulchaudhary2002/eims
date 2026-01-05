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

<body class="max-h-[100vh] bg-gray-50 overflow-hidden relative">

    @include('admin.includes.sidebar')

    @include('admin.includes.header')

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @yield('page-specific-script')
</body>

</html>