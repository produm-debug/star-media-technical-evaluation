<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class ArticleController extends Controller
{
    /**
     * Show the published articles, newest first.
     *
     * Same published-only rule as the API: a null published_at means draft.
     */
    public function index(): View
    {
        $articles = Article::with('category:id,name')
            ->select(['id', 'title', 'summary', 'permalink', 'published_at', 'category_id'])
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->get();

        return view('articles.index', ['articles' => $articles]);
    }

    /**
     * Show a single published article, looked up by its permalink.
     */
    public function show(string $permalink): View
    {
        $article = Article::with('category')
            ->whereNotNull('published_at')
            ->where('permalink', $permalink)
            ->firstOrFail();

        return view('articles.show', ['article' => $article]);
    }
}
