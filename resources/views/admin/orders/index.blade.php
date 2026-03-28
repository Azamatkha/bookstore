@extends('layouts.admin')

@section('title', 'Orders | Admin')

@section('content')
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Orders</p>
        <h1 class="mt-2 text-5xl">Track fulfillment.</h1>
    </div>

    <div class="panel mb-6 p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid gap-4 lg:grid-cols-4">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="field-input" placeholder="Order ID, name, email">
            <select name="status" class="field-select">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">Order</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Wallet</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="table-row">
                            <td class="px-6 py-4 font-semibold text-slate-950">#{{ $order->id }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->user?->name ?? 'Guest' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->items->sum('quantity') }}</td>
                            <td class="px-6 py-4 text-slate-600">@money($order->total_price)</td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($order->wallet_card_number)
                                    {{ substr($order->wallet_card_number, 0, 4) }} **** **** {{ substr($order->wallet_card_number, -4) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
@endsection
