@extends('layouts.admin')

@section('title', 'Categories | Admin')

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Categories</p>
            <h1 class="mt-2 text-5xl">Organize the catalog.</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">Add Category</a>
    </div>

    <div class="panel mb-6 p-6">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="flex flex-col gap-4 lg:flex-row">
            <input type="text" name="search" value="{{ $search }}" class="field-input" placeholder="Search categories">
            <button type="submit" class="btn-primary">Search</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Books</th>
                        <th class="px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="table-row">
                            <td class="px-6 py-4 font-semibold text-slate-950">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $category->slug }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $category->books_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="btn-secondary">View</a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $categories->links() }}
    </div>
@endsection
