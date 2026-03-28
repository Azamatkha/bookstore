<?php

namespace App\Support;

class Currency
{
    public static function format(float|int|string|null $amount): string
    {
        if ($amount === null) {
            return 'N/A';
        }

        $value = (float) $amount;
        $decimals = abs($value - round($value)) < 0.00001 ? 0 : 2;

        return number_format($value, $decimals, '.', ' ') . " so'm";
    }
}
