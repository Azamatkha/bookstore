<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'BookStore')</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/books.ico') }}" sizes="any">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.16),_transparent_32%),linear-gradient(180deg,#f8fbff_0%,#eef6ff_48%,#f8fafc_100%)]">
            <div class="absolute inset-x-0 top-0 h-64 bg-[radial-gradient(circle_at_top_right,_rgba(2,132,199,0.12),_transparent_38%)]"></div>
            @include('layouts.partials.store-header')

            <main class="relative z-10 pb-16">
                <div class="page-shell pt-8">
                    <x-flash-message />
                </div>
                @yield('content')
            </main>

            @include('layouts.partials.store-footer')
        </div>
    </body>
</html>
