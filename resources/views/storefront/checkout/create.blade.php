@extends('layouts.store')

@section('title', __('messages.checkout') . ' | ' . config('app.name', 'Bookstore'))

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.checkout') }}</p>
            <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl">{{ __('messages.finish_order') }}</h1>
        </div>

        <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-6 lg:gap-8 lg:grid-cols-[1fr_0.9fr]">
            @csrf

            <div class="panel p-5 sm:p-6">
                <div class="grid gap-6">
                    <div>
                        <label class="field-label" for="address">{{ __('messages.address') }}</label>
                        <textarea id="address" name="address" rows="5" class="field-textarea" required>{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="phone">{{ __('messages.phone') }}</label>
                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="field-input" required>
                        @error('phone')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="field-label" for="payment_type">{{ __('messages.payment_type') }}</label>
                        <select id="payment_type" name="payment_type" class="field-select" required>
                            @foreach($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->value }}" @selected(old('payment_type') === $paymentType->value)>{{ $paymentType->label() }}</option>
                            @endforeach
                        </select>
                        @error('payment_type')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <aside class="panel p-5 sm:p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.order_summary') }}</p>
                <div class="mt-6 space-y-4">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <p class="font-semibold text-slate-950">{{ $item['book']->title }}</p>
                                <p class="text-sm text-slate-500">{{ __('messages.qty') }} {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-semibold text-slate-900">@money($item['subtotal'])</p>
                        </div>
                    @endforeach
                </div>

                @error('cart')
                    <p class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $message }}</p>
                @enderror

                <div class="mt-6 flex items-center justify-between text-lg font-semibold text-slate-950 sm:text-xl">
                    <span>{{ __('messages.total') }}</span>
                    <span>@money($total)</span>
                </div>

                <button type="submit" class="btn-primary mt-6 w-full">{{ __('messages.place_order') }}</button>
            </aside>
        </form>
    </section>
@endsection
