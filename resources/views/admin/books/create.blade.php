@extends('layouts.admin')

@section('title', 'Create Book | Admin')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Books</p>
            <h1 class="mt-2 text-5xl">Create a new title.</h1>
        </div>
        <a href="{{ route('admin.books.index') }}" class="btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" class="panel p-6">
        @csrf
        @include('admin.books._form')

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary">Create Book</button>
            <a href="{{ route('admin.books.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
