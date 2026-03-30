<aside class="hidden w-72 shrink-0 border-r border-slate-200 bg-slate-950 p-6 text-slate-100 lg:block">
    <div class="flex h-full flex-col">
        <a href="{{ route('admin.dashboard') }}" class="mb-8 flex items-center gap-3">
            <span class="rounded-full bg-amber-400 px-3 py-2 text-sm font-bold uppercase tracking-[0.3em] text-slate-900">BS</span>
            <div>
                <p class="font-serif text-2xl text-white">{{ __('messages.admin_nav') }}</p>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">{{ __('messages.admin_panel') }}</p>
            </div>
        </a>

        <nav class="space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.dashboard') }}</a>
            <a href="{{ route('admin.books.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.books.*') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.books') }}</a>
            <a href="{{ route('admin.categories.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.categories') }}</a>
            <a href="{{ route('admin.authors.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.authors.*') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.authors') }}</a>
            <a href="{{ route('admin.orders.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.orders.*') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.orders') }}</a>
            <a href="{{ route('admin.api-docs') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.api-docs') ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-slate-900' }}">{{ __('messages.api_docs') }}</a>
        </nav>

        <div class="mt-auto space-y-3 rounded-3xl border border-slate-800 bg-slate-900 p-4">
            <div>
                <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('home') }}" class="btn-secondary w-full">{{ __('messages.open_storefront') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-danger w-full">{{ __('messages.logout') }}</button>
            </form>
        </div>
    </div>
</aside>
