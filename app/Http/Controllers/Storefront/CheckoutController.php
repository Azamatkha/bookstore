<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CheckoutController extends Controller
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

        return view('storefront.checkout.create', compact('items', 'total', 'paymentTypes'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $order = $this->orderService->place(
            $request->user(),
            $this->cartService->orderLines(),
            $request->validated(),
        );

        $this->cartService->clear();

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Your order has been placed successfully.');
    }
}
