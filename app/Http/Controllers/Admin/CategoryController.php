<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private function upload($file)
    {
        $fileName = 'categories/' . uniqid() . '.' . $file->getClientOriginalExtension();

        Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
                'Content-Type' => $file->getMimeType(),
            ])
            ->withBody(file_get_contents($file), $file->getMimeType())
            ->put(env('SUPABASE_URL') . '/storage/v1/object/books/' . $fileName);

        return env('SUPABASE_URL') . '/storage/v1/object/public/books/' . $fileName;
    }

    private function delete($url)
    {
        $path = str_replace(
            env('SUPABASE_URL') . '/storage/v1/object/public/books/',
            '',
            $url
        );

        Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
            ])
            ->delete(env('SUPABASE_URL') . '/storage/v1/object/books/' . $path);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $categories = Category::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->withCount('books')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->file('image'));
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($category->image) {
                $this->delete($category->image);
            }

            $data['image'] = $this->upload($request->file('image'));

        } else {
            unset($data['image']);
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image) {
            $this->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }
}