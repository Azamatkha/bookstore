<!-- store-home.blade.php -->
@extends('layouts.store')

@section('title', 'BookStore')

@section('content')
    <section class="page-shell pb-10 pt-6">
        <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <div class="glass-panel hero-panel overflow-hidden p-6 lg:p-8">
                <div class="hero-cinematic" aria-hidden="true"></div>
                <div class="hero-light-pass" aria-hidden="true"></div>
                <div class="hero-grain" aria-hidden="true"></div>
                <div class="hero-vignette" aria-hidden="true"></div>
                <div class="hero-orbit hero-orbit--one" aria-hidden="true"></div>
                <div class="hero-orbit hero-orbit--two" aria-hidden="true"></div>

                <div class="hero-copy relative z-10">
                    <p class="hero-kicker">{{ __('messages.attention_book_lovers') }}</p>
                    <br>
                    <h1 class="hero-quote-heading">{{ __('messages.hero_quote') }}</h1>
                    <p class="hero-quote-author">{{ __('messages.bookstore_owner') }}</p>

                    <p class="hero-support">
                        {{ __('messages.hero_support') }}
                    </p>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#catalog" class="btn-primary">{{ __('messages.explore_catalog') }}</a>
                        <a href="{{ route('cart.index') }}" class="btn-secondary">{{ __('messages.open_cart') }}</a>
                    </div>
                </div>
            </div>

            <div class="featured-stack">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.featured_books') }}</p>
                @foreach($featuredBooks->take(3) as $featuredBook)
                    <a href="{{ route('books.show', ['book' => $featuredBook->slug]) }}" class="featured-card">
                        <div class="h-28 w-20 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-amber-100 to-slate-100">
                            @if($featuredBook->cover_image)
                                <img src="{{ $featuredBook->cover_image }}" alt="{{ $featuredBook->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ $featuredBook->category->name }}</p>
                            <h2 class="mt-1 line-clamp-2 text-xl font-semibold text-slate-950">{{ $featuredBook->title }}</h2>
                            <p class="mt-2 text-sm text-slate-500">{{ $featuredBook->author->name }}</p>
                            <p class="mt-4 text-sm font-semibold text-slate-900">@money($featuredBook->current_price)</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section id="catalog" class="page-shell pb-16">
        <div class="panel mb-8 p-6">
            <form method="GET" action="{{ route('home') }}#catalog" class="grid gap-4 lg:grid-cols-6">
                <div class="lg:col-span-2">
                    <label class="field-label" for="search">{{ __('messages.search_btn') }}</label>
                    <input id="search" type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="field-input" placeholder="{{ __('messages.search_placeholder') }}">
                </div>

                <div>
                    <label class="field-label" for="category">{{ __('messages.category') }}</label>
                    <select id="category" name="category" class="field-select">
                        <option value="">{{ __('messages.all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="author">{{ __('messages.author') }}</label>
                    <select id="author" name="author" class="field-select">
                        <option value="">{{ __('messages.all_authors') }}</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected(($filters['author'] ?? '') == $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label" for="sort">{{ __('messages.sort') }}</label>
                    <select id="sort" name="sort" class="field-select">
                        <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>{{ __('messages.latest') }}</option>
                        <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>{{ __('messages.price_low_to_high') }}</option>
                        <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>{{ __('messages.price_high_to_low') }}</option>
                        <option value="rating_desc" @selected(($filters['sort'] ?? '') === 'rating_desc')>{{ __('messages.top_rated') }}</option>
                        <option value="title_asc" @selected(($filters['sort'] ?? '') === 'title_asc')>{{ __('messages.title') }}</option>
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <button type="submit" class="btn-primary w-full">{{ __('messages.filter') }}</button>
                    <a href="{{ route('home') }}#catalog" class="btn-secondary w-full text-center">{{ __('messages.reset') }}</a>
                </div>
            </form>
        </div>

        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.catalog') }}</p>
                <h2 class="mt-1 text-4xl">{{ __('messages.available_books') }}</h2>
            </div>
            <p class="text-sm text-slate-500">{{ $books->total() }} {{ __('messages.results') }}</p>
        </div>

        @if($books->count())
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($books as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $books->fragment('catalog')->links() }}
            </div>
        @else
            <div class="panel p-10 text-center">
                <h3 class="text-3xl">{{ __('messages.no_books_matched') }}</h3>
                <p class="mt-3 text-slate-500">{{ __('messages.try_different_keyword') }}</p>
            </div>
        @endif
    </section>
@endsection
