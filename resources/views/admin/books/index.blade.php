@extends('layouts.admin')

@section('title', 'Books | Admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Books</p>
            <h1 class="mt-2 text-5xl">Manage inventory.</h1>
        </div>
        <a href="{{ route('admin.books.create') }}" class="btn-primary">Add Book</a>
    </div>

    <div class="panel mb-6 p-6">
        <form method="GET" action="{{ route('admin.books.index') }}" class="grid gap-4 lg:grid-cols-5">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="field-input" placeholder="Search books">
            <select name="category" class="field-select">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="author" class="field-select">
                <option value="">All authors</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" @selected(($filters['author'] ?? '') == $author->id)>{{ $author->name }}</option>
                @endforeach
            </select>
            <select name="sort" class="field-select">
                <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Latest</option>
                <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Price low to high</option>
                <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Price high to low</option>
                <option value="rating_desc" @selected(($filters['sort'] ?? '') === 'rating_desc')>Top rated</option>
            </select>
            <div class="flex gap-3">
                <button type="submit" class="btn-primary w-full">Apply</button>
                <a href="{{ route('admin.books.index') }}" class="btn-secondary w-full text-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">Book</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Author</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Actions</th>
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
                                    <a href="{{ route('admin.books.show', $book) }}" class="btn-secondary">View</a>
                                    <a href="{{ route('admin.books.edit', $book) }}" class="btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">No books found.</td>
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
