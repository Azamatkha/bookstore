<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreHomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->only(['search', 'category', 'author', 'sort', 'min_price', 'max_price']);
        $books = Book::query()
            ->with(['author', 'category'])
            ->filter($filters)
            ->paginate(12)
            ->withQueryString();

        $featuredBooks = Book::query()
            ->with(['author', 'category'])
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $categories = Category::query()->withCount('books')->orderBy('name')->get();
        $authors = Author::query()->orderBy('name')->get();

        return view('store-home', compact('books', 'featuredBooks', 'categories', 'authors', 'filters'));
    }
}
