@extends('layouts.admin')

@section('title', 'Admin Dashboard | BookStore')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Dashboard</p>
            <h1 class="mt-2 text-5xl">BookStore overview.</h1>
        </div>
        <a href="{{ route('home') }}" class="btn-secondary lg:hidden">Open Storefront</a>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Books</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['books'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Categories</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['categories'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Authors</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['authors'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Orders</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">{{ $stats['orders'] }}</p>
        </div>
        <div class="metric-card">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Revenue</p>
            <p class="mt-4 text-4xl font-semibold text-slate-950">@money($stats['revenue'])</p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="table-shell">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Recent Orders</p>
                    <h2 class="mt-1 text-3xl">Latest activity</h2>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-secondary">View all</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-head">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
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
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Inventory</p>
                    <h2 class="mt-1 text-3xl">Low stock</h2>
                </div>
                <a href="{{ route('admin.books.index') }}" class="btn-secondary">Manage books</a>
            </div>

            <div class="mt-6 space-y-4">
                @forelse($lowStockBooks as $book)
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <a href="{{ route('admin.books.show', $book) }}" class="text-lg font-semibold text-slate-950">{{ $book->title }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ $book->author->name }}</p>
                            </div>
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">{{ $book->stock }} left</span>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500">No low stock alerts right now.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
