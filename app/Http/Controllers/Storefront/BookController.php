<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\View\View;

class BookController extends Controller
{
    public function show(Book $book): View
    {
        $book->load(['author', 'category']);

        $relatedBooks = Book::query()
            ->with(['author', 'category'])
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->take(4)
            ->get();

        return view('storefront.books.show', compact('book', 'relatedBooks'));
    }
}
