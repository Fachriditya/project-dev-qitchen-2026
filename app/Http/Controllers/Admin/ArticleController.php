<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $articles = Article::with('admin')
            ->when($search, function ($query, $search) {
                return $query->where('article_title', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                if ($status === 'published') return $query->where('is_published', 1);
                if ($status === 'draft') return $query->where('is_published', 0);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_title' => 'required|string|max:150|unique:articles,article_title',
            'article_category' => 'required|in:News,Tips,Promo',
            'article_content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published' => 'required|boolean',
        ]);

        $data = $request->all();
        // 1. Catat siapa yang nulis
        $data['admin_id'] = Auth::id();
        // 2. Buat slug otomatis dari judul (Contoh: "Promo Merdeka" -> "promo-merdeka")
        $data['article_slug'] = Str::slug($request->article_title);
        
        // 3. Atur waktu publish
        if ($request->is_published) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully!');
    }

    public function show(string $id)
    {
        $article = Article::with('admin')->findOrFail($id);
        return view('admin.articles.show', compact('article'));
    }

    public function edit(string $id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'article_title' => 'required|string|max:150|unique:articles,article_title,' . $article->article_id . ',article_id',
            'article_category' => 'required|in:News,Tips,Promo',
            'article_content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_published' => 'required|boolean',
        ]);

        $data = $request->except(['thumbnail']);
        $data['article_slug'] = Str::slug($request->article_title);

        // Jika tadinya draft, lalu diubah jadi publish, catat waktunya
        if ($request->is_published && !$article->is_published) {
            $data['published_at'] = now();
        } elseif (!$request->is_published) {
            $data['published_at'] = null; // Kembalikan ke draft
        }

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail && Storage::disk('public')->exists($article->thumbnail)) {
                Storage::disk('public')->delete($article->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully!');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        if ($article->thumbnail && Storage::disk('public')->exists($article->thumbnail)) {
            Storage::disk('public')->delete($article->thumbnail);
        }
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully!');
    }
}