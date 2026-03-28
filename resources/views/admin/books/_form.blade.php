@php($book = $book ?? null)

<div class="grid gap-6 lg:grid-cols-2">
    <div class="lg:col-span-2">
        <label class="field-label" for="title">Title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $book?->title) }}" class="field-input" required>
        @error('title')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="slug">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $book?->slug) }}" class="field-input">
        @error('slug')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="published_at">Published at</label>
        <input id="published_at" type="date" name="published_at" value="{{ old('published_at', optional($book?->published_at)->format('Y-m-d')) }}" class="field-input">
        @error('published_at')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label class="field-label" for="description">Description</label>
        <textarea id="description" name="description" rows="6" class="field-textarea">{{ old('description', $book?->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="price">Price (so'm)</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $book?->price) }}" class="field-input" required>
        @error('price')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="discount_price">Discount price (so'm)</label>
        <input id="discount_price" type="number" step="0.01" min="0" name="discount_price" value="{{ old('discount_price', $book?->discount_price) }}" class="field-input">
        @error('discount_price')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="stock">Stock</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $book?->stock ?? 0) }}" class="field-input" required>
        @error('stock')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="cover_image">Cover image</label>
        <input id="cover_image" type="file" name="cover_image" class="field-input">
        @error('cover_image')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="category_id">Category</label>
        <select id="category_id" name="category_id" class="field-select" required>
            <option value="">Choose category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book?->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="field-label" for="author_id">Author</label>
        <select id="author_id" name="author_id" class="field-select" required>
            <option value="">Choose author</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" @selected(old('author_id', $book?->author_id) == $author->id)>{{ $author->name }}</option>
            @endforeach
        </select>
        @error('author_id')
            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if($book?->cover_image)
        <div class="lg:col-span-2">
            <p class="field-label">Current cover</p>
            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-40 rounded-3xl object-cover">
        </div>
    @endif
</div>
