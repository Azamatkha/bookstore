@php($cartCount = collect(session('bookstore.cart', []))->sum())

<header class="relative z-20 border-b border-white/70 bg-white/90 backdrop-blur">
    <div class="page-shell">
        <div class="flex items-center justify-between gap-3 py-3 sm:py-4">
            <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2.5 text-slate-900 sm:gap-3">
                <span class="rounded-full bg-slate-900 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.26em] text-amber-50 sm:px-3 sm:py-1.5 sm:text-xs sm:tracking-[0.3em]">BS</span>
                <span class="truncate font-serif text-lg sm:text-xl lg:text-2xl">BookStore</span>
            </a>

            <details class="relative lg:hidden">
                <summary class="inline-flex h-9 w-9 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 sm:h-10 sm:w-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                    </svg>
                    <span class="sr-only">Open menu</span>
                </summary>

                <div class="absolute right-0 z-30 mt-2 w-[calc(100vw-2rem)] max-w-sm rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-[0_16px_40px_-24px_rgba(15,23,42,0.22)] sm:mt-3 sm:p-4">
                    <div class="grid gap-2">
                        <a href="{{ url('/#catalog') }}" class="nav-btn-secondary w-full">Catalog</a>
                        <a href="{{ route('cart.index') }}" class="nav-btn-secondary w-full">Cart ({{ $cartCount }})</a>
                        @auth
                            <a href="{{ route('wallet.show') }}" class="nav-btn-secondary w-full">Wallet</a>
                            <a href="{{ route('orders.index') }}" class="nav-btn-secondary w-full">My Orders</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="nav-btn-primary w-full">Admin Panel</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="nav-btn-secondary w-full">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-btn-secondary w-full">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="nav-btn-secondary w-full">Login</a>
                            <a href="{{ route('register') }}" class="nav-btn-primary w-full">Create account</a>
                        @endauth
                    </div>
                </div>
            </details>

            <nav class="hidden lg:flex lg:flex-wrap lg:items-center lg:justify-end lg:gap-2 xl:gap-2.5">
                <a href="{{ url('/#catalog') }}" class="nav-btn-secondary">Catalog</a>
                <a href="{{ route('cart.index') }}" class="nav-btn-secondary">Cart ({{ $cartCount }})</a>
                @auth
                    <a href="{{ route('wallet.show') }}" class="nav-btn-secondary">Wallet</a>
                    <a href="{{ route('orders.index') }}" class="nav-btn-secondary">My Orders</a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="nav-btn-primary">Admin Panel</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="nav-btn-secondary">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-btn-secondary">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="nav-btn-primary">Create account</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
