<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentType: string
{
    case CashOnDelivery = 'cash_on_delivery';
    case Card = 'card';
    case BankTransfer = 'bank_transfer';

    public function label(): string
    {
        return Str::headline(str_replace('_', ' ', $this->value));
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
