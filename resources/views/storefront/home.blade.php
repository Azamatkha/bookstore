<!-- home.blade.php -->
@extends('layouts.store')

@section('title', 'BookStore')

@section('content')
    <section class="page-shell pb-8 pt-4 sm:pb-10 sm:pt-5 lg:pb-12">
        <div class="grid gap-4 lg:gap-5 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="glass-panel hero-panel overflow-hidden p-4 sm:p-5 lg:p-7">
                <div class="hero-cinematic" aria-hidden="true"></div>
                <div class="hero-light-pass" aria-hidden="true"></div>
                <div class="hero-grain" aria-hidden="true"></div>
                <div class="hero-vignette" aria-hidden="true"></div>
                <div class="hero-orbit hero-orbit--one" aria-hidden="true"></div>
                <div class="hero-orbit hero-orbit--two" aria-hidden="true"></div>

                <div class="hero-copy relative z-10">
                    <p class="hero-kicker">ATTENTION BOOK LOVERS</p>
                    <h1 class="hero-quote-heading">Don't just read books, live them! Books are the key to unlock the world.</h1>
                    <p class="hero-quote-author">BookStore owner</p>

                    <p class="hero-support">
                        Discover a world of stories waiting to be explored. From thrilling adventures to captivating tales, we have something for every reader.
                        We have a wide range of books for all ages and interests. Whether you're looking for fiction, non-fiction, or anything in between, we have something for you.
                    </p>

                    <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:flex-wrap">
                        <a href="#catalog" class="btn-primary w-full sm:w-auto">Explore Catalog</a>
                        <a href="{{ route('cart.index') }}" class="btn-secondary w-full sm:w-auto">Open Cart</a>
                    </div>
                </div>
            </div>

            <div class="featured-stack">
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-slate-400 sm:text-xs sm:tracking-[0.3em]">Featured Books</p>
                @foreach($featuredBooks->take(3) as $featuredBook)
                    <a href="{{ route('books.show', ['book' => $featuredBook->slug]) }}" class="featured-card">
                        <div class="h-24 w-16 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-amber-100 to-slate-100 sm:h-[6.5rem] sm:w-[4.5rem]">
                            @if($featuredBook->cover_image)
                                <img src="{{ asset('storage/' . $featuredBook->cover_image) }}" alt="{{ $featuredBook->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-400 sm:text-[11px]">{{ $featuredBook->category->name }}</p>
                            <h2 class="mt-1 line-clamp-2 text-base font-semibold text-slate-950 sm:text-lg">{{ $featuredBook->title }}</h2>
                            <p class="mt-1.5 text-xs text-slate-500 sm:text-sm">{{ $featuredBook->author->name }}</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900">@money($featuredBook->current_price)</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="catalog" class="page-shell pb-10 sm:pb-12 lg:pb-14">
        <div class="panel mb-5 p-3 sm:mb-6 sm:p-4">
            <form method="GET" action="{{ route('home') }}" class="grid gap-3 min-[420px]:grid-cols-2 lg:grid-cols-6">
                <div class="min-[420px]:col-span-2 lg:col-span-2">
                    <label class="field-label" for="search">Search</label>
                    <input id="search" type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="field-input" placeholder="Title, author, category">
                </div>

                <div>
                    <label class="field-label" for="category">Category</label>
                    <select id="category" name="category" class="field-select">
                        <option value="">All categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="author">Author</label>
                    <select id="author" name="author" class="field-select">
                        <option value="">All authors</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected(($filters['author'] ?? '') == $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="sort">Sort</label>
                    <select id="sort" name="sort" class="field-select">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Latest</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price low to high</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price high to low</option>
                        <option value="rating_desc" @selected(($filters['sort'] ?? '') === 'rating_desc')>Top rated</option>
                        <option value="title_asc" @selected(($filters['sort'] ?? '') === 'title_asc')>Title</option>
                    </select>
                </div>

                <div class="col-span-full flex flex-col gap-2 min-[420px]:flex-row lg:col-span-1 lg:justify-end">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                    <a href="{{ route('home') }}" class="btn-secondary w-full text-center">Reset</a>
                </div>
            </form>
        </div>

        <div class="mb-4 flex flex-col gap-1.5 sm:mb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Catalog</p>
                <h2 class="mt-1 text-xl sm:text-2xl lg:text-3xl">Available Books</h2>
            </div>
            <p class="text-xs text-slate-500 sm:text-sm">{{ $books->total() }} results</p>
        </div>

        @if($books->count())
            <div class="grid grid-cols-1 gap-3 min-[420px]:grid-cols-2 sm:gap-4 lg:grid-cols-3">
                @foreach($books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>

            <div class="mt-6 sm:mt-8">
                {{ $books->links() }}
            </div>
        @else
            <div class="panel p-6 text-center sm:p-8">
                <h3 class="text-2xl sm:text-3xl">No books matched your filters.</h3>
                <p class="mt-2 text-sm text-slate-500 sm:text-base">Try a different keyword, category, or sort option.</p>
            </div>
        @endif
    </section>
@endsection
