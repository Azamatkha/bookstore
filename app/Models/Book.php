<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'discount_price',
        'stock',
        'cover_image',
        'author_id',
        'category_id',
        'published_at',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'discount_price' => 'integer',
            'published_at' => 'date',
            'rating' => 'decimal:1',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        $sort = $filters['sort'] ?? 'latest';

        return $query
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $innerQuery) use ($search): void {
                    $innerQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('author', fn (Builder $authorQuery) => $authorQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('category', fn (Builder $categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['category'] ?? null, fn (Builder $query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($filters['author'] ?? null, fn (Builder $query, $authorId) => $query->where('author_id', $authorId))
            ->when($filters['min_price'] ?? null, fn (Builder $query, $minPrice) => $query->whereRaw('COALESCE(discount_price, price) >= ?', [(float) $minPrice]))
            ->when($filters['max_price'] ?? null, fn (Builder $query, $maxPrice) => $query->whereRaw('COALESCE(discount_price, price) <= ?', [(float) $maxPrice]))
            ->when($sort === 'price_asc', fn (Builder $query) => $query->orderByRaw('COALESCE(discount_price, price) asc'))
            ->when($sort === 'price_desc', fn (Builder $query) => $query->orderByRaw('COALESCE(discount_price, price) desc'))
            ->when($sort === 'title_asc', fn (Builder $query) => $query->orderBy('title'))
            ->when($sort === 'rating_desc', fn (Builder $query) => $query->orderByDesc('rating'))
            ->when(! in_array($sort, ['price_asc', 'price_desc', 'title_asc', 'rating_desc'], true), fn (Builder $query) => $query->latest());
    }

    protected function currentPrice(): Attribute
    {
        return Attribute::get(fn (): float => (float) ($this->discount_price ?: $this->price));
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::get(fn (): bool => $this->discount_price !== null && (float) $this->discount_price < (float) $this->price);
    }

    protected function discountPercentage(): Attribute
    {
        return Attribute::get(function (): int {
            if (! $this->has_discount || (float) $this->price === 0.0) {
                return 0;
            }

            return (int) round((1 - ((float) $this->discount_price / (float) $this->price)) * 100);
        });
    }

    protected function inStock(): Attribute
    {
        return Attribute::get(fn (): bool => $this->stock > 0);
    }
}
