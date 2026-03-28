<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'total_price',
        'status',
        'address',
        'phone',
        'payment_type',
        'wallet_card_number',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'integer',
            'status' => OrderStatus::class,
            'payment_type' => PaymentType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
