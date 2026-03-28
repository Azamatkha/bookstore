<?php

namespace App\Http\Controllers;

use App\Enums\PaymentType;
use App\Http\Requests\CheckoutRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StoreCheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly OrderService $orderService,
    ) {
    }

    public function create(): View|RedirectResponse
    {
        if (! $this->cartService->hasItems()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $items = $this->cartService->contents();
        $total = $this->cartService->total();
        $paymentTypes = PaymentType::cases();
        $wallet = Wallet::query()->firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'card_number' => Wallet::generateCardNumber(),
                'balance' => 0,
            ],
        );

        return view('store-checkout', compact('items', 'total', 'paymentTypes', 'wallet'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($request, $validated) {
            $isWalletPayment = $validated['payment_type'] === PaymentType::Card->value;

            $order = $this->orderService->place(
                $request->user(),
                $this->cartService->orderLines(),
                $validated,
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

        $this->cartService->clear();

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Your order has been placed successfully.');
    }
}
