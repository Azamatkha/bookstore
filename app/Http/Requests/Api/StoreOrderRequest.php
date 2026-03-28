<?php

namespace App\Http\Requests\Api;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['required', 'string', 'max:30'],
            'payment_type' => ['required', Rule::in(PaymentType::values())],
            'items' => ['required', 'array', 'min:1'],
            'items.*.book_id' => ['required', 'exists:books,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
