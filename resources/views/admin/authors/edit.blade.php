@extends('layouts.admin')

@section('title', __('messages.edit_author_admin_title'))

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.authors') }}</p>
            <h1 class="mt-2 text-5xl">{{ __('messages.edit') }} {{ $author->name }}.</h1>
        </div>
        <a href="{{ route('admin.authors.index') }}" class="btn-secondary">{{ __('messages.back') }}</a>
    </div>

    <form method="POST" action="{{ route('admin.authors.update', $author) }}" enctype="multipart/form-data" class="panel p-6">
        @csrf
        @method('PUT')
        @include('admin.authors._form', ['author' => $author])

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary">{{ __('messages.update_author_btn') }}</button>
            <a href="{{ route('admin.authors.show', $author) }}" class="btn-secondary">{{ __('messages.view') }}</a>
        </div>
    </form>
@endsection
