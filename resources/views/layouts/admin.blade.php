<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin | BookStore')</title>
        <link rel="icon" href="{{ asset('/images/books.ico')}}" sizes="any">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[linear-gradient(180deg,#f8fbff_0%,#eef6ff_100%)]">
            <div class="mx-auto flex min-h-screen max-w-[1920px]">
                @include('layouts.partials.admin-sidebar')

                <main class="min-w-0 flex-1 p-6 lg:p-8">
                    <div class="mb-6 flex flex-wrap gap-2 lg:hidden">
                        <a href="{{ route('admin.dashboard') }}" class="btn-secondary">{{ __('messages.dashboard') }}</a>
                        <a href="{{ route('admin.books.index') }}" class="btn-secondary">{{ __('messages.books') }}</a>
                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">{{ __('messages.categories') }}</a>
                        <a href="{{ route('admin.authors.index') }}" class="btn-secondary">{{ __('messages.authors') }}</a>
                        <a href="{{ route('admin.orders.index') }}" class="btn-secondary">{{ __('messages.orders') }}</a>
                        <a href="{{ route('admin.api-docs') }}" class="btn-secondary">{{ __('messages.api_docs') }}</a>
                        <a href="{{ route('home') }}" class="btn-primary">{{ __('messages.open_storefront') }}</a>
                    </div>

                    <x-flash-message />
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
