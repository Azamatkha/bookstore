<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bio',
        'photo',
        'born_year',
    ];

    protected function casts(): array
    {
        return [
            'born_year' => 'integer',
        ];
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
