@extends('layouts.app')

@section('title', $article->title)

@section('content')
    <a href="{{ route('articles.index') }}" class="btn btn-link px-0 mb-3">&larr; Back to articles</a>

    <article class="card shadow-sm">
        <div class="card-body p-4 p-md-5">
            <span class="badge text-bg-primary mb-3">{{ $article->category->name }}</span>
            <h1 class="h2">{{ $article->title }}</h1>
            <p class="text-secondary">
                <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->format('j F Y') }}</time>
            </p>

            {{-- pre-line keeps the paragraph breaks in the stored text; {{ }} still escapes the content. --}}
            <div class="mt-4" style="white-space: pre-line;">{{ $article->body }}</div>
        </div>
    </article>
@endsection
