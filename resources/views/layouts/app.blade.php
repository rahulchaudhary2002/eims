<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-specific-style')
</head>

<body class="flex flex-col min-h-screen relative">
    @include('includes.header')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4">
            @yield('content')
        </div>
    </div>
    @include('includes.footer')

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