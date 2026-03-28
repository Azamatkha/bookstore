<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->withCount('books')
            ->orderBy('name')
            ->get();

        return response()->json(
            $categories->map(fn (Category $category): array => $this->categoryData($category))->values()
        );
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($this->categoryData($category->loadCount('books')));
    }

    private function categoryData(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image_url' => $category->image ? Storage::url($category->image) : null,
            'books_count' => $category->books_count,
        ];
    }
}
