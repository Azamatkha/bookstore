<?php

namespace App\Http\Controllers\Api;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrderRequest;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $ordersQuery = Order::query()
            ->with(['items.book'])
            ->latest();

        if (! $request->user()->is_admin) {
            $ordersQuery->where('user_id', $request->user()->id);
        }

        $orders = $ordersQuery->get();

        return response()->json(
            $orders->map(fn (Order $order): array => $this->orderData($order))->values()
        );
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if (! $request->user()->is_admin) {
            abort_unless($order->user_id === $request->user()->id, 403);
        }

        return response()->json($this->orderData($order->load(['items.book'])));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($request, $validated) {
            $checkoutData = collect($validated)->except('items')->all();
            $isWalletPayment = $checkoutData['payment_type'] === PaymentType::Card->value;

            $order = $this->orderService->place(
                $request->user(),
                collect($validated['items']),
                $checkoutData,
            );

            if ($isWalletPayment) {
                $wallet = Wallet::query()
                    ->where('user_id', $request->user()->id)
                    ->lockForUpdate()
                    ->first();

                if (! $wallet) {
                    $wallet = Wallet::query()->create([
                        'user_id' => $request->user()->id,
                        'card_number' => Wallet::generateCardNumber(),
                        'balance' => 0,
                    ]);
                }

                if ((int) $wallet->balance < (int) $order->total_price) {
                    throw ValidationException::withMessages([
                        'payment_type' => 'Wallet balance is not enough. Please top up your wallet first.',
                    ]);
                }

                $wallet->decrement('balance', (int) $order->total_price);
                $wallet->refresh();

                WalletTransaction::query()->create([
                    'wallet_id' => $wallet->id,
                    'order_id' => $order->id,
                    'type' => 'order_payment',
                    'amount' => (int) $order->total_price,
                    'balance_after' => (int) $wallet->balance,
                ]);

                $order->update([
                    'wallet_id' => $wallet->id,
                    'wallet_card_number' => $wallet->card_number,
                ]);
            } else {
                $order->update([
                    'wallet_id' => null,
                    'wallet_card_number' => null,
                ]);
            }

            return $order;
        });

        return response()->json(
            $this->orderData($order->load(['items.book'])),
            201
        );
    }

    private function orderData(Order $order): array
    {
        return [
            'id' => $order->id,
            'total_price' => (int) $order->total_price,
            'status' => $this->safeEnumValue($order, 'status'),
            'address' => $order->address,
            'phone' => $order->phone,
            'payment_type' => $this->safeEnumValue($order, 'payment_type'),
            'wallet_id' => $order->wallet_id,
            'wallet_card_number' => $order->wallet_card_number,
            'created_at' => $order->created_at?->toISOString(),
            'items' => $order->items->map(fn ($item): array => [
                'id' => $item->id,
                'book_id' => $item->book_id,
                'book_title' => $item->book_title ?: $item->book?->title,
                'quantity' => $item->quantity,
                'price' => (int) $item->price,
                'subtotal' => (int) $item->subtotal,
            ])->values()->all(),
        ];
    }

    private function safeEnumValue(Order $order, string $attribute): ?string
    {
        try {
            $value = $order->{$attribute};

            if (is_object($value) && property_exists($value, 'value')) {
                return (string) $value->value;
            }

            return is_string($value) ? $value : null;
        } catch (\Throwable) {
            $raw = $order->getRawOriginal($attribute);

            return $raw !== null ? (string) $raw : null;
        }
    }
}
