@extends('layouts.admin')

@section('title', __('messages.books_admin_title'))

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.books') }}</p>
            <h1 class="mt-2 text-5xl">{{ __('messages.manage_inventory') }}</h1>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn-primary">{{ __('messages.add_book') }}</a>
    </div>

    <div class="panel mb-6 p-6">
        <form method="GET" action="{{ route('admin.books.index') }}" class="grid gap-4 lg:grid-cols-5">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="field-input" placeholder="{{ __('messages.search_books') }}">
            <select name="category" class="field-select">
                <option value="">{{ __('messages.all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="author" class="field-select">
                <option value="">{{ __('messages.all_authors') }}</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(($filters['author'] ?? '') == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
            <select name="sort" class="field-select">
                <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>{{ __('messages.latest') }}</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>{{ __('messages.price_low_to_high') }}</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>{{ __('messages.price_high_to_low') }}</option>
                <option value="rating_desc" @selected(($filters['sort'] ?? '') === 'rating_desc')>{{ __('messages.top_rated') }}</option>
            </select>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary w-full">{{ __('messages.apply') }}</button>
                <a href="{{ route('admin.books.index') }}" class="btn-secondary w-full text-center">{{ __('messages.reset') }}</a>
            </div>
        </form>
    </div>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.book') }}</th>
                        <th class="px-6 py-4">{{ __('messages.category') }}</th>
                        <th class="px-6 py-4">{{ __('messages.author') }}</th>
                        <th class="px-6 py-4">{{ __('messages.price') }}</th>
                        <th class="px-6 py-4">{{ __('messages.stock') }}</th>
                        <th class="px-6 py-4">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr class="table-row">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.books.show', $book) }}" class="font-semibold text-slate-950">{{ $book->title }}</a>
                                <p class="mt-1 text-sm text-slate-500">{{ $book->slug }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->category->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->author->name }}</td>
                            <td class="px-6 py-4 text-slate-600">@money($book->current_price)</td>
                            <td class="px-6 py-4 text-slate-600">{{ $book->stock }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.books.show', $book) }}" class="btn-secondary">{{ __('messages.view') }}</a>
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_books_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $books->links() }}
    </div>
@endsection
