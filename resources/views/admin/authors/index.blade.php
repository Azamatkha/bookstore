@extends('layouts.admin')

@section('title', __('messages.authors_admin_title'))

@section('content')
    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.authors') }}</p>
            <h1 class="mt-2 text-5xl">{{ __('messages.manage_authors') }}</h1>
        </div>
        <a href="{{ route('admin.authors.create') }}" class="btn-primary">{{ __('messages.add_author') }}</a>
    </div>

    <div class="panel mb-6 p-6">
        <form method="GET" action="{{ route('admin.authors.index') }}" class="flex flex-col gap-4 lg:flex-row">
            <input type="text" name="search" value="{{ $search }}" class="field-input" placeholder="{{ __('messages.search_authors') }}">
            <button type="submit" class="btn-primary">{{ __('messages.search_btn') }}</button>
            <a href="{{ route('admin.authors.index') }}" class="btn-secondary">{{ __('messages.reset') }}</a>
        </form>
    </div>

    <div class="table-shell">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="table-head">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.author') }}</th>
                        <th class="px-6 py-4">{{ __('messages.born_year') }}</th>
                        <th class="px-6 py-4">{{ __('messages.books') }}</th>
                        <th class="px-6 py-4">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        <tr class="table-row">
                            <td class="px-6 py-4 font-semibold text-slate-950">{{ $author->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $author->born_year ?: 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $author->books_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.authors.show', $author) }}" class="btn-secondary">{{ __('messages.view') }}</a>
                                    <a href="{{ route('admin.authors.edit', $author) }}" class="btn-secondary">{{ __('messages.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.authors.destroy', $author) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">{{ __('messages.no_authors_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $authors->links() }}
    </div>
@endsection
