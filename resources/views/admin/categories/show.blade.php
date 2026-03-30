@extends('layouts.admin')

@section('title', $category->name . ' | ' . __('messages.admin_nav'))

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.category_detail') }}</p>
            <h1 class="mt-2 text-5xl">{{ $category->name }}</h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-primary">{{ __('messages.edit') }}</a>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">{{ __('messages.back') }}</a>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
        <div class="panel overflow-hidden">
            <div class="aspect-[4/3] bg-gradient-to-br from-amber-100 to-slate-100">
                @if($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="h-full w-full object-cover">
                @endif
            </div>
        </div>

        <div class="space-y-4">
            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.slug') }}</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $category->slug }}</p>

                <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.books_count') }}</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $category->books_count }}</p>
            </div>

            <div class="panel p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">{{ __('messages.description') }}</p>
                <p class="mt-4 leading-7 text-slate-600">{{ $category->description ?: __('messages.no_description_provided') }}</p>
            </div>
        </div>
    </div>
@endsection
