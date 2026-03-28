<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletTopUpRequest;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Support\Currency;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function show(Request $request): View
    {
        $wallet = Wallet::query()->firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'card_number' => Wallet::generateCardNumber(),
                'balance' => 0,
            ],
        );

        $transactions = $wallet->transactions()->limit(20)->get();

        return view('wallet', compact('wallet', 'transactions'));
    }

    public function topUp(WalletTopUpRequest $request): RedirectResponse
    {
        $amount = (int) $request->validated('amount');

        DB::transaction(function () use ($request, $amount): void {
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

            $wallet->increment('balance', $amount);
            $wallet->refresh();

            WalletTransaction::query()->create([
                'wallet_id' => $wallet->id,
                'order_id' => null,
                'type' => 'top_up',
                'amount' => $amount,
                'balance_after' => (int) $wallet->balance,
            ]);
        });

        return redirect()
            ->route('wallet.show')
            ->with('success', 'Wallet topped up by ' . Currency::format($amount) . '.');
    }
}
