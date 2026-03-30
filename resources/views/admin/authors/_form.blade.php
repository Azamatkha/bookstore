@php($author = $author ?? null)

<div class="grid gap-6">
    <div>
        <label class="field-label" for="name">{{ __('messages.name') }}</label>
        <input id="name" type="text" name="name" value="{{ old('name', $author?->name) }}" class="field-input" required>
        @error('name')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="bio">{{ __('messages.bio') }}</label>
        <textarea id="bio" name="bio" rows="6" class="field-textarea">{{ old('bio', $author?->bio) }}</textarea>
        @error('bio')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="born_year">{{ __('messages.born_year') }}</label>
        <input id="born_year" type="number" min="0" max="{{ now()->year }}" name="born_year" value="{{ old('born_year', $author?->born_year) }}" class="field-input">
        @error('born_year')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="photo">{{ __('messages.photo') }}</label>
        <input id="photo" type="file" name="photo" class="field-input">
        @error('photo')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if($author?->photo)
        <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="h-40 rounded-3xl object-cover">
    @endif
</div>
