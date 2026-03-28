<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'born_year' => ['nullable', 'integer', 'min:0', 'max:' . now()->year],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
