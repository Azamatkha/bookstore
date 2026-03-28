<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price !== null ? (float) $this->discount_price : null,
            'current_price' => (float) $this->current_price,
            'discount_percentage' => $this->discount_percentage,
            'stock' => $this->stock,
            'rating' => (float) $this->rating,
            'cover_image_url' => $this->cover_image ? Storage::url($this->cover_image) : null,
            'published_at' => optional($this->published_at)?->toDateString(),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'author' => AuthorResource::make($this->whenLoaded('author')),
        ];
    }
}
