@extends('layouts.admin')

@section('title', 'Edit Book | Admin')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Books</p>
            <h1 class="mt-2 text-5xl">Edit {{ $book->title }}.</h1>
        </div>
        <a href="{{ route('admin.books.index') }}" class="btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" class="panel p-6">
        @csrf
        @method('PUT')
        @include('admin.books._form', ['book' => $book])

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary">Update Book</button>
            <a href="{{ route('admin.books.show', $book) }}" class="btn-secondary">View</a>
        </div>
    </form>
@endsection
