@extends('layouts.store')

@section('title', 'My Wallet | Bookstore')

@section('content')
    <section class="page-shell py-8">
        <div class="mb-8">
            <h1 class="mt-2 text-5xl">Wallet</h1>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1fr_0.9fr]">
            <div class="panel p-6 border-[#0f172a] border-1 bg-white">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Your internal card</p>
                <p class="mt-4 text-3xl font-semibold tracking-[0.1em] text-slate-950">{{ chunk_split($wallet->card_number, 4, ' ') }}</p>
                <br>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 border-t border-slate-100 pt-6">Card owner</p>
                <p class="mt-4 text-3xl font-semibold tracking-[0.1em] text-slate-950">{{ $wallet->user->name ?? 'Unknown' }}</p>
                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                    <span class="text-sm text-slate-500">Current balance</span>
                    <span class="text-3xl font-semibold text-slate-950">@money($wallet->balance)</span>
                </div>
            </div>

            <div class="panel p-6 border-[#0f172a] border-1 bg-white">
                <h2 class="text-3xl">Top up wallet</h2>
                <form method="POST" action="{{ route('wallet.top-up') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="field-label" for="card_number">Card number</label>
                        <input id="card_number" type="text" name="card_number" value="{{ old('card_number') }}" class="field-input" placeholder="8600123412341234" required>
                        @error('card_number')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="expiry">Expiry (MM/YYYY)</label>
                        <input id="expiry" type="text" name="expiry" value="{{ old('expiry') }}" class="field-input" placeholder="03/2027" required>
                        @error('expiry')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="amount">Amount</label>
                        <input id="amount" type="number" min="1" step="1" name="amount" value="{{ old('amount') }}" class="field-input" placeholder="100000" required>
                        @error('amount')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">Top up</button>
                </form>
            </div>
        </div>

        <div class="panel mt-8 p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-3xl">Transaction History</h2>
                <p class="text-sm text-slate-500">{{ $transactions->count() }} records</p>
            </div>

            @if($transactions->isEmpty())
                <p class="mt-4 text-slate-500">No wallet transactions yet.</p>
            @else
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-left text-slate-500">
                                <th class="px-3 py-2">Date</th>
                                <th class="px-3 py-2">Type</th>
                                <th class="px-3 py-2">Amount</th>
                                <th class="px-3 py-2">Balance After</th>
                                <th class="px-3 py-2">Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="border-b border-slate-100 text-slate-700">
                                    <td class="px-3 py-2">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-3 py-2">
                                        {{ $transaction->type === 'top_up' ? 'Top Up' : 'Order Payment' }}
                                    </td>
                                    <td class="px-3 py-2 font-semibold">
                                        {{ $transaction->type === 'top_up' ? '+' : '-' }}@money($transaction->amount)
                                    </td>
                                    <td class="px-3 py-2">@money($transaction->balance_after)</td>
                                    <td class="px-3 py-2">
                                        {{ $transaction->order_id ? ('#' . $transaction->order_id) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
