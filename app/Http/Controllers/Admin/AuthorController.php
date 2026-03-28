<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AuthorRequest;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $authors = Author::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
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
        $data['photo'] = $request->hasFile('photo')
            ? $request->file('photo')->store('authors', 'public')
            : null;

        Author::create($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author created successfully.');
    }

    public function show(Author $author): View
    {
        $author->loadCount('books');

        return view('admin.authors.show', compact('author'));
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
                Storage::disk('public')->delete($author->photo);
            }

            $data['photo'] = $request->file('photo')->store('authors', 'public');
        } else {
            unset($data['photo']);
        }

        $author->update($data);

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author updated successfully.');
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->photo) {
            Storage::disk('public')->delete($author->photo);
        }

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}
