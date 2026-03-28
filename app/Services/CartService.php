<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'bookstore.cart';

    public function contents(): Collection
    {
        $items = collect(session(self::SESSION_KEY, []))
            ->mapWithKeys(fn ($quantity, $bookId) => [(int) $bookId => (int) $quantity])
            ->filter(fn (int $quantity) => $quantity > 0);

        if ($items->isEmpty()) {
            return collect();
        }

        $books = Book::query()
            ->with(['author', 'category'])
            ->whereIn('id', $items->keys())
            ->get()
            ->keyBy('id');

        $validItems = [];

        $lines = $items->map(function (int $quantity, int $bookId) use ($books, &$validItems) {
            $book = $books->get($bookId);

            if (! $book) {
                return null;
            }

            $validItems[$bookId] = $quantity;

            return [
                'book' => $book,
                'quantity' => $quantity,
                'subtotal' => (float) $book->current_price * $quantity,
            ];
        })->filter()->values();

        if (count($validItems) !== $items->count()) {
            session([self::SESSION_KEY => $validItems]);
        }

        return $lines;
    }

    public function orderLines(): Collection
    {
        return $this->contents()->map(fn (array $line) => [
            'book_id' => $line['book']->id,
            'quantity' => $line['quantity'],
        ]);
    }

    public function add(Book $book, int $quantity = 1): void
    {
        $cart = session(self::SESSION_KEY, []);
        $currentQuantity = (int) ($cart[$book->id] ?? 0);
        $cart[$book->id] = min($currentQuantity + $quantity, $book->stock);

        session([self::SESSION_KEY => $cart]);
    }

    public function update(Book $book, int $quantity): void
    {
        $cart = session(self::SESSION_KEY, []);

        if ($quantity <= 0) {
            unset($cart[$book->id]);
        } else {
            $cart[$book->id] = min($quantity, $book->stock);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(Book $book): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$book->id]);

        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) collect(session(self::SESSION_KEY, []))->sum();
    }

    public function total(): float
    {
        return (float) $this->contents()->sum('subtotal');
    }

    public function hasItems(): bool
    {
        return $this->count() > 0;
    }
}
