<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthorController extends Controller
{
    private function upload($file)
    {
        $fileName = 'authors/' . uniqid() . '.' . $file->getClientOriginalExtension();

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

        $authors = Author::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->withCount('books')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.authors.index', compact('authors', 'search'));
    }

    public function create(): View
    {
        return view('admin.authors.create');
    }

    public function store(AuthorRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->upload($request->file('photo'));
        }

        Author::create($data);

        return redirect()->route('admin.authors.index')
            ->with('success', __('messages.author_created_successfully'));
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(AuthorRequest $request, Author $author): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {

            if ($author->photo) {
                $this->delete($author->photo);
            }

            $data['photo'] = $this->upload($request->file('photo'));

        } else {
            unset($data['photo']);
        }

        $author->update($data);

        return redirect()->route('admin.authors.index')
            ->with('success', __('messages.author_updated_successfully'));
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->photo) {
            $this->delete($author->photo);
        }

        $author->delete();

        return redirect()->route('admin.authors.index')
            ->with('success', __('messages.author_deleted_successfully'));
    }
}