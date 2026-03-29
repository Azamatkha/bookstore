<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookStore') }}</title>
        <link rel="icon" href="{{ asset('/images/books.ico') }}" sizes="any">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top,_rgba(14,165,233,0.14),_transparent_30%),linear-gradient(180deg,#f8fbff_0%,#eef6ff_100%)]">
            <div class="absolute inset-x-0 top-0 h-64 bg-[radial-gradient(circle_at_top_right,_rgba(2,132,199,0.1),_transparent_35%)]"></div>
            @include('layouts.navigation')

            <main class="relative z-10 page-shell py-10">
                <x-flash-message />

                @isset($header)
                    <header class="mb-8 panel px-6 py-5">
                        {{ $header }}
                    </header>
                @endisset

                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </body>
</html>
