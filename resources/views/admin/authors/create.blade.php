@extends('layouts.admin')

@section('title', 'Create Author | Admin')

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">Authors</p>
            <h1 class="mt-2 text-5xl">Create author.</h1>
        </div>
        <a href="{{ route('admin.authors.index') }}" class="btn-secondary">Back</a>
    </div>

    <form method="POST" action="{{ route('admin.authors.store') }}" enctype="multipart/form-data" class="panel p-6">
        @csrf
        @include('admin.authors._form')

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary">Create Author</button>
            <a href="{{ route('admin.authors.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
@endsection
