<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class WalletTopUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'card_number' => ['required', 'regex:/^\d{16}$/'],
            'expiry' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/\d{4}$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    [$month, $year] = explode('/', (string) $value);
                    $expiryDate = Carbon::create((int) $year, (int) $month, 1)->endOfMonth();

                    if ($expiryDate->lt(now())) {
                        $fail('The card expiry date must be in the future.');
                    }
                },
            ],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }
}
