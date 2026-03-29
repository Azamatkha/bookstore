@extends('layouts.store')

@section('title', 'Your Cart | Bookstore')

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="mb-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Cart</p>
            <h1 class="mt-2 text-3xl sm:text-4xl lg:text-5xl">Your reading shortlist.</h1>
        </div>

        @if($items->count())
            <div class="grid gap-6 lg:gap-8 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="panel p-4 sm:p-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center">
                                <div class="h-28 w-20 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-amber-100 to-slate-100 sm:h-32 sm:w-24">
                                    @if($item['book']->cover_image)
                                        <img src="{{ asset('storage/' . $item['book']->cover_image) }}" alt="{{ $item['book']->title }}" class="h-full w-full object-cover">
                                    @endif
                                </div>

                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('books.show', ['book' => $item['book']->slug]) }}" class="text-xl font-semibold text-slate-950 sm:text-2xl">{{ $item['book']->title }}</a>
                                    <p class="mt-2 text-sm text-slate-500">{{ $item['book']->author->name }} · {{ $item['book']->category->name }}</p>
                                    <p class="mt-4 text-lg font-semibold text-slate-900">@money($item['book']->current_price)</p>
                                </div>

                                <div class="flex flex-wrap items-center gap-3">
                                    <form method="POST" action="{{ route('cart.update', $item['book']) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" min="1" max="{{ $item['book']->stock }}" name="quantity" value="{{ $item['quantity'] }}" class="field-input w-20 sm:w-24">
                                        <button type="submit" class="btn-secondary">Update</button>
                                    </form>

                                    <form method="POST" action="{{ route('cart.destroy', $item['book']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="panel h-fit p-5 sm:p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Summary</p>
                    <h2 class="mt-2 text-3xl">Checkout details</h2>

                    <div class="mt-6 space-y-4 border-t border-slate-100 pt-6">
                        <div class="flex items-center justify-between text-sm text-slate-500">
                            <span>Items</span>
                            <span>{{ $items->sum('quantity') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-lg font-semibold text-slate-950">
                            <span>Total</span>
                            <span>@money($total)</span>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        @auth
                            <a href="{{ route('checkout.create') }}" class="btn-primary w-full">Proceed to Checkout</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary w-full">Login to Checkout</a>
                        @endauth
                        <a href="{{ route('home') }}" class="btn-secondary w-full">Continue Shopping</a>
                    </div>
                </aside>
            </div>
        @else
            <div class="panel p-8 text-center sm:p-10">
                <h2 class="text-3xl sm:text-4xl">Your cart is empty.</h2>
                <p class="mt-3 text-slate-500">Start with a title that catches your attention.</p>
                <a href="{{ route('home') }}" class="btn-primary mt-6">Browse Books</a>
            </div>
        @endif
    </section>
@endsection
