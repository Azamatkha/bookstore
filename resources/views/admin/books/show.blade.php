@extends('layouts.admin')

@section('title', $book->title . ' | ' . __('messages.admin_nav'))

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.book_detail') }}</p>
            <h1 class="mt-2 text-5xl">{{ $book->title }}</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.books.edit', $book) }}" class="btn-primary">{{ __('messages.edit') }}</a>
            <a href="{{ route('admin.books.index') }}" class="btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="panel overflow-hidden">
            <div class="aspect-[4/5] bg-gradient-to-br from-amber-100 to-slate-100">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="panel p-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.slug') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->slug }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.price') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">@money($book->price)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Discount Price</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->discount_price ? \App\Support\Currency::format($book->discount_price) : __('messages.n_a') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.stock') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->stock }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.category') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.author') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->author->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Published</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($book->published_at)->format('M d, Y') ?? __('messages.n_a') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.rating') }}</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ number_format((float) $book->rating, 1) }}</p>
                    </div>
                </div>
            </div>

            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.description') }}</p>
                <p class="mt-4 leading-7 text-slate-600">{{ $book->description ?: __('messages.no_description_provided') }}</p>
            </div>
        </div>
    </div>
@endsection
