<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(): JsonResponse
    {
        $authors = Author::query()
            ->withCount('books')
            ->orderBy('name')
            ->get();

        return response()->json(
            $authors->map(fn (Author $author): array => $this->authorData($author))->values()
        );
    }

    public function show(Author $author): JsonResponse
    {
        return response()->json($this->authorData($author->loadCount('books')));
    }

    private function authorData(Author $author): array
    {
        return [
            'id' => $author->id,
            'name' => $author->name,
            'bio' => $author->bio,
            'born_year' => $author->born_year,
            'photo_url' => $author->photo ? Storage::url($author->photo) : null,
            'books_count' => $author->books_count,
        ];
    }
}
