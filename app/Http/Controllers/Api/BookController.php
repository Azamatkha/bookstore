<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $books = Book::query()
            ->with(['author', 'category'])
            ->filter($request->only(['search', 'category', 'author', 'sort', 'min_price', 'max_price']))
            ->get();

        return response()->json(
            $books->map(fn (Book $book): array => $this->bookData($book))->values()
        );
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json($this->bookData($book->load(['author', 'category'])));
    }

    private function bookData(Book $book): array
    {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'slug' => $book->slug,
            'description' => $book->description,
            'price' => (int) $book->price,
            'discount_price' => $book->discount_price !== null ? (int) $book->discount_price : null,
            'current_price' => (int) $book->current_price,
            'discount_percentage' => $book->discount_percentage,
            'stock' => $book->stock,
            'rating' => (float) $book->rating,
            'cover_image_url' => $book->cover_image ? Storage::url($book->cover_image) : null,
            'published_at' => optional($book->published_at)?->toDateString(),
            'category' => $book->category ? [
                'id' => $book->category->id,
                'name' => $book->category->name,
                'slug' => $book->category->slug,
                'description' => $book->category->description,
                'image_url' => $book->category->image ? Storage::url($book->category->image) : null,
            ] : null,
            'author' => $book->author ? [
                'id' => $book->author->id,
                'name' => $book->author->name,
                'bio' => $book->author->bio,
                'born_year' => $book->author->born_year,
                'photo_url' => $book->author->photo ? Storage::url($book->author->photo) : null,
            ] : null,
        ];
    }
}
