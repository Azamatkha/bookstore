@extends('layouts.store')

@section('title', 'My Orders | Bookstore')

@section('content')
    <section class="page-shell py-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Orders</p>
            <h1 class="mt-2 text-5xl">Purchase history.</h1>
        </div>

        @if($orders->count())
            <div class="space-y-4">
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="panel block p-6 transition hover:-translate-y-0.5">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">Order #{{ $order->id }}</p>
                                    <x-status-badge :status="$order->status" />
                                </div>
                                <p class="mt-4 text-sm text-slate-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">{{ $item->book_title }} × {{ $item->quantity }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-slate-400">Total</p>
                                <p class="text-3xl font-semibold text-slate-950">@money($order->total_price)</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="panel p-10 text-center">
                <h2 class="text-4xl">No orders yet.</h2>
                <p class="mt-3 text-slate-500">Your completed purchases will appear here.</p>
                <a href="{{ route('home') }}" class="btn-primary mt-6">Browse Books</a>
            </div>
        @endif
    </section>
@endsection
