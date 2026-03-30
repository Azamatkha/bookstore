@extends('layouts.admin')

@section('title', 'Admin Dashboard | BookStore')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.dashboard') }}</p>
            <h1 class="mt-2 text-5xl">{{ __('messages.bookstore_overview') }}</h1>
        </div>
        <a href="{{ route('home') }}" class="btn-secondary lg:hidden">{{ __('messages.open_storefront') }}</a>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.books') }}</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['books'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.categories') }}</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['categories'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.authors') }}</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['authors'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.orders') }}</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['orders'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.revenue') }}</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">@money($stats['revenue'])</p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="table-shell">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.recent_orders') }}</p>
                    <h2 class="mt-1 text-3xl">{{ __('messages.latest_activity') }}</h2>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary">{{ __('messages.view_all') }}</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.order') }}</th>
                            <th class="px-6 py-4">{{ __('messages.customer') }}</th>
                            <th class="px-6 py-4">{{ __('messages.total') }}</th>
                            <th class="px-6 py-4">{{ __('messages.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                            <tr class="table-row">
                                <td class="px-6 py-4 font-semibold text-slate-900">
                                    <a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $order->user?->name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 text-slate-600">@money($order->total_price)</td>
                                <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_orders_yet') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.inventory') }}</p>
                    <h2 class="mt-1 text-3xl">{{ __('messages.low_stock') }}</h2>
                </div>
                <a href="{{ route('admin.books.index') }}" class="btn-secondary">{{ __('messages.manage_books') }}</a>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($lowStockBooks as $book)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <a href="{{ route('admin.books.show', $book) }}" class="text-lg font-semibold text-slate-950">{{ $book->title }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ $book->author->name }}</p>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">{{ $book->stock }} {{ __('messages.left') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500">{{ __('messages.no_low_stock_alerts') }}</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
