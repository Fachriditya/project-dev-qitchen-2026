<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::where('is_published', true)->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('article_title', 'like', '%' . $request->search . '%')
                  ->orWhere('article_content', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('article_category', $request->category);
        }

        $featuredArticle = (clone $query)->first();

        $articles = $query->where('article_id', '!=', $featuredArticle?->article_id ?? 0) 
                          ->paginate(3)
                          ->withQueryString(); 

        return view('public.articles.index', compact('featuredArticle', 'articles'));
    }

    public function show($slug)
    {
        $article = Article::where('article_slug', $slug)->firstOrFail();

        $relatedArticles = Article::where('is_published', true)
                                  ->where('article_id', '!=', $article->article_id)
                                  ->latest()
                                  ->take(3)
                                  ->get();

        return view('public.articles.show', compact('article', 'relatedArticles'));
    }
}