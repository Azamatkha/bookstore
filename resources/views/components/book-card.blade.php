@props(['book'])

<article class="panel flex h-full flex-col overflow-hidden">
    <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="relative block">
        <div class="h-48 overflow-hidden bg-gradient-to-br from-amber-100 via-white to-slate-100 sm:h-52 lg:h-48 xl:h-52">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.25),_transparent_35%),linear-gradient(135deg,#f8fafc,#fff7ed)] px-6 text-center">
                    <p class="line-clamp-3 font-serif text-xl text-slate-700 sm:text-2xl">{{ $book->title }}</p>
                </div>
            @endif
        </div>
        @if($book->has_discount)
            <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-white sm:left-4 sm:top-4 sm:px-3 sm:text-xs">
                -{{ $book->discount_percentage }}%
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-4 sm:p-5">
        <div class="mb-2.5 flex items-center justify-between gap-2">
            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.16em] text-amber-800 sm:px-3 sm:text-[11px]">
                {{ $book->category->name }}
            </span>
            <span class="shrink-0 text-xs font-medium text-slate-500 sm:text-sm">★ {{ number_format((float) $book->rating, 1) }}</span>
        </div>

        <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="mb-1.5">
            <h3 class="line-clamp-2 text-lg leading-snug sm:text-xl">{{ $book->title }}</h3>
        </a>

        <p class="mb-3 line-clamp-1 text-sm text-slate-500">{{ $book->author->name }}</p>

        <div class="mt-auto space-y-3">
            <div class="flex items-end gap-2.5">
                <p class="text-xl font-semibold text-slate-950 sm:text-2xl">@money($book->current_price)</p>
                @if($book->has_discount)
                    <p class="text-sm text-slate-400 line-through">@money($book->price)</p>
                @endif
            </div>

            <div class="grid gap-2">
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <button type="submit" class="btn-primary w-full" @disabled(! $book->in_stock)>
                        {{ $book->in_stock ? 'Add to Cart' : 'Out of Stock' }}
                    </button>
                </form>

                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                    <input type="hidden" name="checkout" value="1">
                    <button type="submit" class="btn-secondary w-full" @disabled(! $book->in_stock)>
                        Buy Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</article>
