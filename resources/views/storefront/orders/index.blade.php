@extends('layouts.store')

@section('title', 'My Orders | Bookstore')

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.orders') }}</p>
            <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl">{{ __('messages.purchase_history') }}</h1>
        </div>

        @if($orders->count())
            <div class="space-y-4">
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="panel block p-5 transition hover:-translate-y-0.5 sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-400">{{ __('messages.order') }} #{{ $order->id }}</p>
                                    <x-status-badge :status="$order->status" />
                                </div>
                                <p class="mt-4 text-sm text-slate-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700 sm:text-sm">{{ $item->book_title }} × {{ $item->quantity }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="text-left lg:text-right">
                                <p class="text-sm text-slate-400">{{ __('messages.total') }}</p>
                                <p class="text-2xl font-semibold text-slate-950 sm:text-3xl">@money($order->total_price)</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="panel p-8 text-center sm:p-10">
                <h2 class="text-3xl sm:text-4xl">{{ __('messages.no_orders_yet') }}</h2>
                <p class="mt-3 text-slate-500">{{ __('messages.completed_purchases_empty') }}</p>
                <a href="{{ route('home') }}" class="btn-primary mt-6">{{ __('messages.browse_books') }}</a>
            </div>
        @endif
    </section>
@endsection
