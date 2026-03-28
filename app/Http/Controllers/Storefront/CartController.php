<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function index(): View
    {
        $items = $this->cartService->contents();
        $total = $this->cartService->total();

        return view('storefront.cart.index', compact('items', 'total'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'checkout' => ['nullable', 'boolean'],
        ]);

        $book = Book::findOrFail($data['book_id']);

        if ($book->stock < 1) {
            return back()->with('error', 'This book is currently out of stock.');
        }

        $this->cartService->add($book, $data['quantity'] ?? 1);

        return ($data['checkout'] ?? false)
            ? redirect()->route('checkout.create')
            : back()->with('success', 'Book added to cart.');
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($book->stock < 1) {
            $this->cartService->remove($book);

            return redirect()->route('cart.index')->with('error', 'This book is no longer available.');
        }

        $this->cartService->update($book, $data['quantity']);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->cartService->remove($book);

        return redirect()->route('cart.index')->with('success', 'Book removed from cart.');
    }
}
