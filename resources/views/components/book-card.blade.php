<!-- book-card.blade.php -->
@props(['book'])

<article class="panel flex h-full flex-col overflow-hidden">
    <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="relative block">
        <div class="h-48 overflow-hidden bg-gradient-to-br from-amber-100 via-white to-slate-100 min-[420px]:h-52 lg:h-56">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.25),_transparent_35%),linear-gradient(135deg,#f8fafc,#fff7ed)] px-4 text-center">
                    <p class="line-clamp-3 font-serif text-lg text-slate-700 sm:text-xl">{{ $book->title }}</p>
                </div>
            @endif
        </div>
        @if($book->has_discount)
            <span class="absolute left-2.5 top-2.5 rounded-full bg-rose-500 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-[0.16em] text-white sm:left-3 sm:top-3 sm:px-2.5 sm:text-[10px]">
                -{{ $book->discount_percentage }}%
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-3 sm:p-4">
        <div class="mb-2 flex items-center justify-between gap-2">
            <span class="rounded-full bg-amber-100 px-2 py-1 text-[9px] font-semibold uppercase tracking-[0.14em] text-amber-800 sm:px-2.5 sm:text-[10px]">
                {{ $book->category->name }}
            </span>
            <span class="shrink-0 text-[11px] font-medium text-slate-500 sm:text-xs">★ {{ number_format((float) $book->rating, 1) }}</span>
        </div>

        <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="mb-1">
            <h3 class="line-clamp-2 text-base font-semibold leading-snug sm:text-lg">{{ $book->title }}</h3>
        </a>

        <p class="mb-2.5 line-clamp-1 text-xs text-slate-500 sm:text-sm">{{ $book->author->name }}</p>

        <div class="mt-auto space-y-2.5">
            <div class="flex items-end gap-2">
                <p class="text-lg font-semibold text-slate-950 sm:text-xl">@money($book->current_price)</p>
                @if($book->has_discount)
                    <p class="text-xs text-slate-400 line-through sm:text-sm">@money($book->price)</p>
                @endif
            </div>

            <div class="grid grid-cols-[2.5rem_minmax(0,1fr)] gap-2">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button
                        type="submit"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 transition duration-300 hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 hover:shadow-[0_14px_28px_-22px_rgba(15,23,42,0.35)] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-none"
                        @disabled(! $book->in_stock)
                        aria-label="{{ $book->in_stock ? 'Add ' . $book->title . ' to cart' : 'Out of stock' }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.75 3.5h1.7l1.9 9.1a1 1 0 00.98.79h8.55a1 1 0 00.97-.75l1.53-5.64H6.04" />
                            <circle cx="9.25" cy="18.25" r="1.35" />
                            <circle cx="16.5" cy="18.25" r="1.35" />
                        </svg>
                        <span class="sr-only">Add to Cart</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <input type="hidden" name="checkout" value="1">
                    <button type="submit" class="btn-primary w-full rounded-xl px-3 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 disabled:hover:shadow-none sm:px-3.5" @disabled(! $book->in_stock)>
                        {{ $book->in_stock ? 'Buy Now' : 'Out of Stock' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</article>
