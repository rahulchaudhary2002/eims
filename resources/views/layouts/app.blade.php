<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="icon" href="{{ asset('assets/images/icon.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('page-specific-style')
</head>

<body class="bg-gray-50 font-sans">
    @yield('page-specific-modal')
    @include('includes.header')
    @if(request()->routeIs('home') || request()->routeIs('login') || request()->routeIs('register') || request()->routeIs('institution.index') || request()->routeIs('course'))
    @yield('content')
    @else
    <div class="max-w-7xl mx-auto px-4 mt-24 pb-8">
        @yield('content')
    </div>
    @endif
    @include('includes.footer')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navMenu = document.getElementById('navMenu');

            mobileMenuBtn.addEventListener('click', function() {
                navMenu.classList.toggle('active');
                if (navMenu.classList.contains('active')) {
                    navMenu.style.left = '0';
                    this.innerHTML = '<i class="fas fa-times"></i>';
                } else {
                    navMenu.style.left = '-100%';
                    this.innerHTML = '<i class="fas fa-bars"></i>';
                }
            });

            // Close mobile menu when clicking on a link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    navMenu.classList.remove('active');
                    navMenu.style.left = '-100%';
                    mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';

                    // Update active nav link
                    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Header scroll effect
            window.addEventListener('scroll', function() {
                const header = document.getElementById('header');
                if (window.scrollY > 100) {
                    header.classList.add('scrolled');
                    header.style.padding = '10px 0';
                } else {
                    header.classList.remove('scrolled');
                    header.style.padding = '0';
                }
            });
        });
    </script>

    @yield('page-specific-script')
</body>

</html>