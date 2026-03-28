@props(['book'])

<article class="panel flex h-full flex-col overflow-hidden">
    <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="relative block">
        <div class="aspect-[4/5] overflow-hidden bg-gradient-to-br from-amber-100 via-white to-slate-100">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(251,191,36,0.25),_transparent_35%),linear-gradient(135deg,#f8fafc,#fff7ed)] px-8 text-center">
                    <p class="font-serif text-3xl text-slate-700">{{ $book->title }}</p>
                </div>
            @endif
        </div>
        @if($book->has_discount)
            <span class="absolute left-4 top-4 rounded-full bg-rose-500 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-white">
                -{{ $book->discount_percentage }}%
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="mb-3 flex items-center justify-between gap-3">
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-800">
                {{ $book->category->name }}
            </span>
            <span class="text-sm font-medium text-slate-500">★ {{ number_format((float) $book->rating, 1) }}</span>
        </div>

        <a href="{{ route('books.show', ['book' => $book->slug]) }}" class="mb-2">
            <h3 class="text-2xl leading-tight">{{ $book->title }}</h3>
        </a>

        <p class="mb-4 text-sm text-slate-500">{{ $book->author->name }}</p>

        <div class="mt-auto space-y-4">
            <div class="flex items-end gap-3">
                <p class="text-2xl font-semibold text-slate-950">@money($book->current_price)</p>
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
