@php($category = $category ?? null)

<div class="grid gap-6">
    <div>
        <label class="field-label" for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category?->name) }}" class="field-input" required>
        @error('name')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="slug">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $category?->slug) }}" class="field-input">
        @error('slug')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="description">Description</label>
        <textarea id="description" name="description" rows="5" class="field-textarea">{{ old('description', $category?->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="image">Image</label>
        <input id="image" type="file" name="image" class="field-input">
        @error('image')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if($category?->image)
        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="h-40 rounded-3xl object-cover">
    @endif
</div>
