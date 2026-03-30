@extends('layouts.store')

@section('title', __('messages.wallet_title') . ' | ' . config('app.name', 'Bookstore'))

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="mb-8">
            <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl">{{ __('messages.wallet') }}</h1>
        </div>

        <div class="grid gap-6 lg:gap-8 lg:grid-cols-[1fr_0.9fr]">
            <div class="panel border border-slate-200 bg-white p-5 sm:p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.internal_card') }}</p>
                <p class="mt-4 break-all text-xl font-semibold tracking-[0.08em] text-slate-950 sm:text-2xl lg:text-3xl">{{ chunk_split($wallet->card_number, 4, ' ') }}</p>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400 border-t border-slate-100 pt-6">{{ __('messages.card_owner') }}</p>
                <p class="mt-4 text-2xl font-semibold tracking-[0.08em] text-slate-950 sm:text-3xl">{{ $wallet->user->name ?? __('messages.unknown') }}</p>
                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-6">
                    <span class="text-sm text-slate-500">{{ __('messages.current_balance') }}</span>
                    <span class="text-2xl font-semibold text-slate-950 sm:text-3xl">@money($wallet->balance)</span>
                </div>
            </div>

            <div class="panel border border-slate-200 bg-white p-5 sm:p-6">
                <h2 class="text-2xl sm:text-3xl">{{ __('messages.top_up_wallet') }}</h2>
                <form method="POST" action="{{ route('wallet.top-up') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label class="field-label" for="card_number">{{ __('messages.card_number') }}</label>
                        <input id="card_number" type="text" name="card_number" value="{{ old('card_number') }}" class="field-input" placeholder="8600123412341234" required>
                        @error('card_number')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="expiry">{{ __('messages.expiry_date') }}</label>
                        <input id="expiry" type="text" name="expiry" value="{{ old('expiry') }}" class="field-input" placeholder="03/2027" required>
                        @error('expiry')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="amount">{{ __('messages.amount') }}</label>
                        <input id="amount" type="number" min="1" step="1" name="amount" value="{{ old('amount') }}" class="field-input" placeholder="100000" required>
                        @error('amount')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">{{ __('messages.top_up_btn') }}</button>
                </form>
            </div>
        </div>

        <div class="panel mt-8 p-5 sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-2xl sm:text-3xl">{{ __('messages.transaction_history') }}</h2>
                <p class="text-sm text-slate-500">{{ $transactions->count() }} {{ __('messages.records') }}</p>
            </div>

            @if($transactions->isEmpty())
                <p class="mt-4 text-slate-500">{{ __('messages.no_wallet_transactions') }}</p>
            @else
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-left text-slate-500">
                                <th class="px-3 py-2">{{ __('messages.date') }}</th>
                                <th class="px-3 py-2">{{ __('messages.type') }}</th>
                                <th class="px-3 py-2">{{ __('messages.amount_th') }}</th>
                                <th class="px-3 py-2">{{ __('messages.balance_after') }}</th>
                                <th class="px-3 py-2">{{ __('messages.order') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $transaction)
                                <tr class="border-b border-slate-100 text-slate-700">
                                    <td class="px-3 py-2">{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                                    <td class="px-3 py-2">
                                        {{ $transaction->type === 'top_up' ? __('messages.top_up') : __('messages.order_payment') }}
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
