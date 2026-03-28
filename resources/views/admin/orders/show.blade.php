@extends('layouts.admin')

@section('title', 'Order #' . $order->id . ' | Admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Order Detail</p>
            <h1 class="mt-2 text-5xl">Order #{{ $order->id }}</h1>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Back</a>
    </div>

    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="panel p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl">Order Items</h2>
                <x-status-badge :status="$order->status" />
            </div>

            <div class="mt-6 space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <p class="font-semibold text-slate-950">{{ $item->book_title }}</p>
                            <p class="text-sm text-slate-500">Qty {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">@money($item->price)</p>
                            <p class="text-sm text-slate-500">@money($item->subtotal)</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <div class="panel p-6">
                <h2 class="text-3xl">Status</h2>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <select name="status" class="field-select">
                        @foreach($statuses as $status)
                            <option value="{{ $status->value }}" @selected($order->status->value === $status->value)>{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                    <button type="submit" class="btn-primary w-full">Update Status</button>
                </form>
            </div>

            <div class="panel p-6">
                <h2 class="text-3xl">Customer</h2>
                <div class="mt-6 space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Name</span>
                        <span class="font-semibold text-slate-900">{{ $order->user?->name ?? 'Guest' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Email</span>
                        <span class="font-semibold text-slate-900">{{ $order->user?->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Phone</span>
                        <span class="font-semibold text-slate-900">{{ $order->phone }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Payment</span>
                        <span class="font-semibold text-slate-900">{{ $order->payment_type->label() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Wallet card</span>
                        <span class="font-semibold text-slate-900">
                            {{ $order->wallet_card_number ? substr($order->wallet_card_number, 0, 4) . ' **** **** ' . substr($order->wallet_card_number, -4) : '-' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-100 pt-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Address</p>
                    <p class="mt-3 text-slate-700">{{ $order->address }}</p>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                    <p class="text-lg font-semibold text-slate-900">Total</p>
                    <p class="text-3xl font-semibold text-slate-950">@money($order->total_price)</p>
                </div>
            </div>
        </div>
    </div>
@endsection
