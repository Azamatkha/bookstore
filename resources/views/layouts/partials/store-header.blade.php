@php($cartCount = collect(session('bookstore.cart', []))->sum())

<header class="relative z-20 border-b border-white/70 bg-white/90 backdrop-blur">
    <div class="page-shell">
        <div class="flex items-center justify-between gap-2 py-2.5 sm:gap-3 sm:py-3.5">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2 text-slate-900 sm:gap-2.5">
                <span class="rounded-full bg-slate-900 px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.24em] text-amber-50 sm:px-3 sm:py-1.5 sm:text-[10px] sm:tracking-[0.28em]">BS</span>
                <span class="truncate font-serif text-base min-[420px]:text-lg sm:text-xl lg:text-2xl">BookStore</span>
            </a>

            <details class="relative shrink-0 lg:hidden">
                <summary class="inline-flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Open menu</span>
                </summary>

                <div class="absolute right-0 z-30 mt-2 w-[min(22rem,calc(100vw-1.5rem))] rounded-[1.35rem] border border-slate-200 bg-white p-2.5 shadow-[0_16px_40px_-24px_rgba(15,23,42,0.22)] sm:mt-3 sm:rounded-[1.5rem] sm:p-3">
                    <div class="grid gap-1.5">
                        <div class="flex justify-center gap-4 py-2 text-sm font-medium text-slate-500">
                            <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">EN</a>
                            <a href="{{ route('lang.switch', 'uz') }}" class="{{ app()->getLocale() === 'uz' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">UZ</a>
                            <a href="{{ route('lang.switch', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">RU</a>
                        </div>
                        <a href="{{ url('/#catalog') }}" class="nav-btn-secondary w-full">{{ __('messages.catalog') }}</a>
                        <a href="{{ route('cart.index') }}" class="nav-btn-secondary w-full">{{ __('messages.cart') }} ({{ $cartCount }})</a>
                        @auth
                            <a href="{{ route('wallet.show') }}" class="nav-btn-secondary w-full">{{ __('messages.wallet') }}</a>
                            <a href="{{ route('orders.index') }}" class="nav-btn-secondary w-full">{{ __('messages.my_orders') }}</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="nav-btn-primary w-full">{{ __('messages.admin_panel') }}</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="nav-btn-secondary w-full">{{ __('messages.profile_nav') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-btn-secondary w-full">{{ __('messages.logout') }}</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="nav-btn-secondary w-full">{{ __('messages.login') }}</a>
                            <a href="{{ route('register') }}" class="nav-btn-primary w-full">{{ __('messages.create_account') }}</a>
                        @endauth
                    </div>
                </div>
            </details>

            <nav class="hidden lg:flex lg:flex-wrap lg:items-center lg:justify-end lg:gap-1.5 xl:gap-2">
                <div class="mr-6 flex items-center gap-2 text-sm font-medium text-slate-500">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">EN</a> <span class="text-slate-300">|</span>
                    <a href="{{ route('lang.switch', 'uz') }}" class="{{ app()->getLocale() === 'uz' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">UZ</a> <span class="text-slate-300">|</span>
                    <a href="{{ route('lang.switch', 'ru') }}" class="{{ app()->getLocale() === 'ru' ? 'font-bold text-slate-950' : 'hover:text-slate-950' }}">RU</a>
                </div>

                <a href="{{ url('/#catalog') }}" class="nav-btn-secondary">{{ __('messages.catalog') }}</a>
                <a href="{{ route('cart.index') }}" class="nav-btn-secondary">{{ __('messages.cart') }} ({{ $cartCount }})</a>
                @auth
                    <a href="{{ route('wallet.show') }}" class="nav-btn-secondary">{{ __('messages.wallet') }}</a>
                    <a href="{{ route('orders.index') }}" class="nav-btn-secondary">{{ __('messages.my_orders') }}</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn-primary">{{ __('messages.admin_panel') }}</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="nav-btn-secondary">{{ __('messages.profile_nav') }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-btn-secondary">{{ __('messages.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn-secondary">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="nav-btn-primary">{{ __('messages.create_account') }}</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
