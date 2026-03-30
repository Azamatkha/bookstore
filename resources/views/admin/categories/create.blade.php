@extends('layouts.admin')

@section('title', __('messages.create_category_admin_title'))

@section('content')
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-400">{{ __('messages.categories') }}</p>
            <h1 class="mt-2 text-5xl">{{ __('messages.create_category') }}</h1>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">{{ __('messages.back') }}</a>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="panel p-6">
        @csrf
        @include('admin.categories._form')

        <div class="mt-8 flex gap-3">
            <button type="submit" class="btn-primary">{{ __('messages.create_category_btn') }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">{{ __('messages.cancel') }}</a>
        </div>
    </form>
@endsection
