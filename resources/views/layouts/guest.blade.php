<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BookStore') }}</title>
        <link rel="icon" href="{{ Vite::asset('resources/images/books.ico') }}" sizes="any">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="auth-page min-h-screen">
            <div class="page-shell flex min-h-screen flex-col justify-center py-12">
                <div class="mx-auto mb-6 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-slate-900">
                        <span class="rounded-full bg-slate-900 px-3 py-2 text-sm font-semibold uppercase tracking-[0.3em] text-amber-50">BS</span>
                        <span class="font-serif text-3xl">BookStore</span>
                    </a>
                </div>

                <div class="auth-card mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
