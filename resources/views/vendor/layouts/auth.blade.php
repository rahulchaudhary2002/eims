<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Administrator Settings</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-specific-style')
</head>

<body class="bg-gray-100 min-h-screen flex justify-center items-center">
    <div class="flex justify-center items-center h-full">
        <div class="grid md:grid-cols-2 bg-white p-5 rounded-2xl gap-5 md:max-w-[700px] shadow-md">
            <div class="hidden md:block max-w-[320px]">
                <img class="rounded-2xl" src="{{ asset('assets/images/auth.png') }}" alt="Auth" height="160" width="320" />
            </div>
            <div class="flex justify-center flex-col items-center gap-2 md:pr-2 min-[425px]:w-[330px] max-[425px]:w-[280px] max-[345px]:w-[250px]">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>