@extends('layouts.store')

@section('title', 'Order #' . $order->id . ' | Bookstore')

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Order Detail</p>
                <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl">Order #{{ $order->id }}</h1>
            </div>
            <x-status-badge :status="$order->status" class="self-start" />
        </div>

        <div class="grid gap-6 lg:gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="panel p-5 sm:p-6">
                <h2 class="text-2xl sm:text-3xl">Items</h2>
                <div class="mt-6 space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <p class="font-semibold text-slate-950">{{ $item->book_title }}</p>
                                <p class="text-sm text-slate-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">@money($item->price)</p>
                                <p class="text-sm text-slate-500">@money($item->subtotal)</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-4">
                <div class="panel p-5 sm:p-6">
                    <h2 class="text-2xl sm:text-3xl">Summary</h2>
                    <div class="mt-6 space-y-3 text-sm text-slate-600">
                        <div class="flex justify-between">
                            <span>Payment type</span>
                            <span class="font-semibold text-slate-900">{{ $order->payment_type->label() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phone</span>
                            <span class="font-semibold text-slate-900">{{ $order->phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Placed</span>
                            <span class="font-semibold text-slate-900">{{ $order->created_at->format('M d, Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Shipping Address</p>
                        <p class="mt-3 text-slate-700">{{ $order->address }}</p>
                    </div>
                </div>

                <div class="panel p-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-slate-900">Total Paid</p>
                        <p class="text-2xl font-semibold text-slate-950 sm:text-3xl">@money($order->total_price)</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="btn-secondary mt-6 w-full">Back to Orders</a>
                </div>
            </aside>
        </div>
    </section>
@endsection
