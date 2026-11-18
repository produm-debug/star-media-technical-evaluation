<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Return published articles as JSON, newest first, each with its category.
     *
     * Design assumption (not an explicit assessment requirement): an article
     * whose published_at is null is treated as a draft and is excluded.
     */
    public function index(): JsonResponse
    {
        $articles = Article::with('category:id,name')
            ->select(['id', 'title', 'summary', 'body', 'permalink', 'published_at', 'category_id'])
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->get();

        return response()->json(['data' => $articles]);
    }
}
