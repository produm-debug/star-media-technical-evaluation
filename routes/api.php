<?php

use App\Http\Controllers\Api\ArticleController;
use Illuminate\Support\Facades\Route;

// Laravel adds the /api prefix automatically to routes registered through the `api:` option.
Route::get('/articles', [ArticleController::class, 'index']);
