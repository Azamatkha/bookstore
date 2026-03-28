<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless((bool) $request->user()?->is_admin, 403);

        $wallets = Wallet::query()
            ->with('user')
            ->withCount('orders')
            ->withSum('orders', 'total_price')
            ->orderByDesc('id')
            ->get();

        return response()->json(
            $wallets->map(fn (Wallet $wallet): array => [
                'id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'user_name' => $wallet->user?->name,
                'user_email' => $wallet->user?->email,
                'card_number' => $wallet->card_number,
                'balance' => (int) $wallet->balance,
                'orders_paid_count' => (int) $wallet->orders_count,
                'orders_paid_total' => (int) ($wallet->orders_sum_total_price ?? 0),
                'updated_at' => $wallet->updated_at?->toISOString(),
            ])->values()
        );
    }

    public function transactions(Request $request, Wallet $wallet): JsonResponse
    {
        abort_unless((bool) $request->user()?->is_admin, 403);

        $transactions = $wallet->transactions()
            ->with('order')
            ->limit(100)
            ->get();

        return response()->json([
            'wallet' => [
                'id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'card_number' => $wallet->card_number,
                'balance' => (int) $wallet->balance,
            ],
            'transactions' => $transactions->map(fn ($transaction): array => [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'amount' => (int) $transaction->amount,
                'balance_after' => (int) $transaction->balance_after,
                'order_id' => $transaction->order_id,
                'created_at' => $transaction->created_at?->toISOString(),
            ])->values(),
        ]);
    }
}
