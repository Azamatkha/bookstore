@extends('layouts.store')

@section('title', $book->title . ' | Bookstore')

@section('content')
    <section class="page-shell py-6 sm:py-8">
        <div class="grid gap-6 lg:gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="panel overflow-hidden">
                <div class="aspect-[4/5] bg-gradient-to-br from-amber-100 via-white to-slate-100">
                    @if($book->cover_image)
                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full items-center justify-center px-10 text-center">
                            <p class="font-serif text-4xl text-slate-700">{{ $book->title }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-5 sm:space-y-6">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-800">{{ $book->category->name }}</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">★ {{ number_format((float) $book->rating, 1) }}</span>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-800">
                            {{ $book->in_stock ? $book->stock . ' ' . __('messages.in_stock') : __('messages.out_of_stock') }}
                        </span>
                    </div>

                    <h1 class="text-3xl leading-tight sm:text-4xl lg:text-5xl">{{ $book->title }}</h1>
                    <p class="text-base text-slate-500 sm:text-lg">{{ __('messages.by_author', ['author' => $book->author->name]) }}</p>
                    <p class="max-w-3xl text-sm leading-6 text-slate-600 sm:text-base sm:leading-7">{{ $book->description }}</p>
                </div>

                <div class="panel p-5 sm:p-6">
                    <div class="flex flex-wrap items-end gap-4">
                        <p class="text-3xl font-semibold text-slate-950 sm:text-4xl">@money($book->current_price)</p>
                        @if($book->has_discount)
                            <div>
                                <p class="text-sm text-slate-400 line-through">@money($book->price)</p>
                                <p class="text-sm font-semibold text-rose-500">{{ __('messages.save_percentage', ['percentage' => $book->discount_percentage]) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <form method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="btn-primary w-full" @disabled(! $book->in_stock)>{{ __('messages.add_to_cart_btn') }}</button>
                        </form>

                        <form method="POST" action="{{ route('cart.store') }}">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <input type="hidden" name="checkout" value="1">
                            <button type="submit" class="btn-secondary w-full" @disabled(! $book->in_stock)>{{ __('messages.buy_now') }}</button>
                        </form>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="panel p-4 sm:p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.published') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($book->published_at)->format('M d, Y') ?? __('messages.n_a') }}</p>
                    </div>
                    <div class="panel p-4 sm:p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.category') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->category->name }}</p>
                    </div>
                    <div class="panel p-4 sm:p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.author') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->author->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedBooks->count())
        <section class="page-shell pb-16">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.related_titles') }}</p>
                    <h2 class="mt-1 text-xl sm:text-2xl lg:text-3xl">{{ __('messages.continue_browsing') }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 min-[420px]:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                @foreach($relatedBooks as $relatedBook)
                    <x-book-card :book="$relatedBook" />
                @endforeach
            </div>
        </section>
    @endif
@endsection
