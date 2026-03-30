<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class BookController extends Controller
{
    private function uploadToSupabase($file)
    {
        $fileName = 'books/' . uniqid() . '.' . $file->getClientOriginalExtension();

        Http::withOptions(['verify' => false]) // ❗ local uchun
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                'Content-Type' => $file->getMimeType(),
            ])
            ->withBody(file_get_contents($file), $file->getMimeType())
            ->put(
                env('SUPABASE_URL') . '/storage/v1/object/books/' . $fileName
            );

        return env('SUPABASE_URL') . '/storage/v1/object/public/books/' . $fileName;
    }

    private function deleteFromSupabase($url)
    {
        $path = str_replace(
            env('SUPABASE_URL') . '/storage/v1/object/public/books/',
            '',
            $url
        );

        Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
            ])
            ->delete(
                env('SUPABASE_URL') . '/storage/v1/object/books/' . $path
            );
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'author', 'sort']);

        $books = Book::query()
            ->with(['author', 'category'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories', 'authors', 'filters'));
    }

    public function create(): View
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.books.create', compact('authors', 'categories'));
    }

    public function store(BookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->uploadToSupabase($request->file('cover_image'));
        }

        Book::create($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', __('messages.book_created_successfully'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories', 'authors'));
    }

    public function update(BookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {

            // eski rasmni o‘chirish
            if ($book->cover_image) {
                $this->deleteFromSupabase($book->cover_image);
            }

            // yangi rasm
            $data['cover_image'] = $this->uploadToSupabase($request->file('cover_image'));

        } else {
            unset($data['cover_image']);
        }

        $book->update($data);

        return redirect()
            ->route('admin.books.index')
            ->with('success', __('messages.book_updated_successfully'));
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->cover_image) {
            $this->deleteFromSupabase($book->cover_image);
        }

        $book->delete();

        return redirect()
            ->route('admin.books.index')
            ->with('success', __('messages.book_deleted_successfully'));
    }
    public function show(Book $book): View
    {
        $book->load(['author', 'category']);

        return view('admin.books.show', compact('book'));
    }
}