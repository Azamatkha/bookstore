@extends('layouts.store')

@section('title', 'BookStore')

@section('content')
    <section class="page-shell pb-8 pt-4 sm:pb-10 sm:pt-6 lg:pb-12">
        <div class="grid gap-5 lg:gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="glass-panel hero-panel overflow-hidden p-5 sm:p-6 lg:p-8">
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

                    <div class="mt-6 flex flex-col gap-2.5 sm:flex-row sm:flex-wrap">
                        <a href="#catalog" class="btn-primary w-full sm:w-auto">Explore Catalog</a>
                        <a href="{{ route('cart.index') }}" class="btn-secondary w-full sm:w-auto">Open Cart</a>
                    </div>
                </div>
            </div>

            <div class="featured-stack">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400 sm:text-sm sm:tracking-[0.3em]">Featured Books</p>
                @foreach($featuredBooks->take(3) as $featuredBook)
                    <a href="{{ route('books.show', ['book' => $featuredBook->slug]) }}" class="featured-card">
                        <div class="h-24 w-16 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-amber-100 to-slate-100 sm:h-28 sm:w-20">
                            @if($featuredBook->cover_image)
                                <img src="{{ asset('storage/' . $featuredBook->cover_image) }}" alt="{{ $featuredBook->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 sm:text-xs">{{ $featuredBook->category->name }}</p>
                            <h2 class="mt-1 line-clamp-2 text-lg font-semibold text-slate-950 sm:text-xl">{{ $featuredBook->title }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $featuredBook->author->name }}</p>
                            <p class="mt-3 text-sm font-semibold text-slate-900">@money($featuredBook->current_price)</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="catalog" class="page-shell pb-12 sm:pb-16">
        <div class="panel mb-6 p-4 sm:mb-8 sm:p-6">
            <form method="GET" action="{{ route('home') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
                <div class="sm:col-span-2 lg:col-span-2">
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

                <div class="flex flex-col gap-2 sm:col-span-2 sm:flex-row lg:col-span-1 lg:justify-end xl:flex-row">
                    <button type="submit" class="btn-primary w-full">Filter</button>
                    <a href="{{ route('home') }}" class="btn-secondary w-full text-center">Reset</a>
                </div>
            </form>
        </div>

        <div class="mb-4 flex flex-col gap-2 sm:mb-6 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Catalog</p>
                <h2 class="mt-1 text-2xl sm:text-3xl lg:text-4xl">Available Books</h2>
            </div>
            <p class="text-sm text-slate-500">{{ $books->total() }} results</p>
        </div>

        @if($books->count())
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $books->links() }}
            </div>
        @else
            <div class="panel p-8 text-center sm:p-10">
                <h3 class="text-2xl sm:text-3xl">No books matched your filters.</h3>
                <p class="mt-3 text-slate-500">Try a different keyword, category, or sort option.</p>
            </div>
        @endif
    </section>
@endsection
