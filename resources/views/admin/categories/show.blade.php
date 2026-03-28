@extends('layouts.admin')

@section('title', $category->name . ' | Admin')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Category Detail</p>
            <h1 class="mt-2 text-5xl">{{ $category->name }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-primary">Edit</a>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Back</a>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="panel overflow-hidden">
            <div class="aspect-[4/3] bg-gradient-to-br from-amber-100 to-slate-100">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Slug</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $category->slug }}</p>

                <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Books count</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $category->books_count }}</p>
            </div>

            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Description</p>
                <p class="mt-4 leading-7 text-slate-600">{{ $category->description ?: 'No description provided.' }}</p>
            </div>
        </div>
    </div>
@endsection
