@extends('layouts.admin')

@section('title', $book->title . ' | Admin')

@section('content')
    <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Book Detail</p>
            <h1 class="mt-2 text-5xl">{{ $book->title }}</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.books.edit', $book) }}" class="btn-primary">Edit</a>
            <a href="{{ route('admin.books.index') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="panel overflow-hidden">
            <div class="aspect-[4/5] bg-gradient-to-br from-amber-100 to-slate-100">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="panel p-6">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Slug</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->slug }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Price</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">@money($book->price)</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Discount Price</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->discount_price ? \App\Support\Currency::format($book->discount_price) : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Stock</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->stock }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Category</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Author</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ $book->author->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Published</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($book->published_at)->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Rating</p>
                        <p class="mt-2 text-lg font-semibold text-slate-900">{{ number_format((float) $book->rating, 1) }}</p>
                    </div>
                </div>
            </div>

            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Description</p>
                <p class="mt-4 leading-7 text-slate-600">{{ $book->description ?: 'No description provided.' }}</p>
            </div>
        </div>
    </div>
@endsection
