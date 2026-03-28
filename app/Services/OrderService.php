<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function place(User $user, Collection $items, array $checkoutData): Order
    {
        $normalizedItems = $items
            ->map(fn (array $item) => [
                'book_id' => (int) ($item['book_id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
            ])
            ->filter(fn (array $item) => $item['book_id'] > 0 && $item['quantity'] > 0)
            ->values();

        if ($normalizedItems->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Your cart is empty.',
            ]);
        }

        return DB::transaction(function () use ($user, $normalizedItems, $checkoutData): Order {
            $books = Book::query()
                ->whereIn('id', $normalizedItems->pluck('book_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $orderItems = $normalizedItems->map(function (array $item) use ($books): array {
                $book = $books->get($item['book_id']);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'cart' => 'One of the selected books no longer exists.',
                    ]);
                }

                if ($book->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'cart' => "Not enough stock for {$book->title}.",
                    ]);
                }

                return [
                    'book' => $book,
                    'book_id' => $book->id,
                    'book_title' => $book->title,
                    'quantity' => $item['quantity'],
                    'price' => (float) $book->current_price,
                ];
            });

            $total = $orderItems->sum(fn (array $item) => $item['price'] * $item['quantity']);

            $order = $user->orders()->create([
                'total_price' => $total,
                'status' => OrderStatus::Pending->value,
                'address' => $checkoutData['address'],
                'phone' => $checkoutData['phone'],
                'payment_type' => $checkoutData['payment_type'],
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create([
                    'book_id' => $item['book_id'],
                    'book_title' => $item['book_title'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $item['book']->decrement('stock', $item['quantity']);
            }

            return $order->load(['items.book', 'user']);
        });
    }
}
