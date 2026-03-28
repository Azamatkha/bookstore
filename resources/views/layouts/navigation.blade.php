@php($isAdmin = auth()->user()?->is_admin)

<nav x-data="{ open: false }" class="relative z-10 border-b border-white/60 bg-white/70 backdrop-blur">
    <div class="page-shell">
        <div class="flex h-15 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-slate-900">
                <span class="rounded-full bg-slate-900 px-3 py-2 text-sm font-semibold uppercase tracking-[0.3em] text-amber-50">BS</span>
                <span class="font-serif text-3xl font-bold">BookStore</span>
            </a>

            <div class="hidden items-center gap-6 md:flex">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') || request()->routeIs('books.show') ? 'text-slate-950' : 'text-slate-500' }}">Store</a>
                <a href="{{ route('cart.index') }}" class="text-sm font-medium {{ request()->routeIs('cart.*') ? 'text-slate-950' : 'text-slate-500' }}">Cart</a>
                <a href="{{ route('orders.index') }}" class="text-sm font-medium {{ request()->routeIs('orders.*') ? 'text-slate-950' : 'text-slate-500' }}">Orders</a>
                @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium {{ request()->routeIs('admin.*') ? 'text-slate-950' : 'text-slate-500' }}">Admin</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="text-sm font-medium {{ request()->routeIs('profile.*') ? 'text-slate-950' : 'text-slate-500' }}">Profile</a>
            </div>

            <div class="hidden items-center gap-3 md:flex">
                <span class="rounded-full bg-amber-100 px-3 py-2 text-sm font-medium text-amber-900">
                    {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">Logout</button>
                </form>
            </div>

            <button @click="open = !open" type="button" class="inline-flex rounded-full border border-slate-200 bg-white p-2 text-slate-700 md:hidden">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition class="border-t border-slate-100 bg-white md:hidden">
        <div class="page-shell space-y-3 py-4">
            <a href="{{ route('home') }}" class="block text-sm font-medium text-slate-700">Store</a>
            <a href="{{ route('cart.index') }}" class="block text-sm font-medium text-slate-700">Cart</a>
            <a href="{{ route('orders.index') }}" class="block text-sm font-medium text-slate-700">Orders</a>
            @if($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="block text-sm font-medium text-slate-700">Admin</a>
            @endif
            <a href="{{ route('profile.edit') }}" class="block text-sm font-medium text-slate-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary w-full">Logout</button>
            </form>
        </div>
    </div>
</nav>
