<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'author', 'sort']);
        $books = Book::query()
            ->with(['author', 'category'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();
        $categories = Category::query()->orderBy('name')->get();
        $authors = Author::query()->orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories', 'authors', 'filters'));
    }

    public function create(): View
    {
        $authors = Author::query()->orderBy('name')->get();
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.books.create', compact('authors', 'categories'));
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['cover_image'] = $request->hasFile('cover_image')
            ? $request->file('cover_image')->store('books', 'public')
            : null;

        Book::create($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book): View
    {
        $book->load(['category', 'author']);

        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::query()->orderBy('name')->get();
        $authors = Author::query()->orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories', 'authors'));
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        } else {
            unset($data['cover_image']);
        }

        $book->update($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
