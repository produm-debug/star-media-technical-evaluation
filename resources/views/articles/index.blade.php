@extends('layouts.app')

@section('title', 'Latest articles')

@section('content')
    @if ($articles->isEmpty())
        <div class="alert alert-info">No articles have been published yet.</div>
    @else
        <div class="row align-items-center g-3 mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">Latest articles</h1>
            </div>
            <div class="col-md-4">
                <input
                    type="search"
                    id="article-search"
                    class="form-control"
                    placeholder="Search by title..."
                    aria-label="Search articles by title"
                    autocomplete="off"
                >
            </div>
        </div>

        <div class="row g-4">
            @foreach ($articles as $article)
                <div class="col-md-6 col-lg-4 article-item" data-title="{{ $article->title }}">
                    <article class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <span class="badge text-bg-primary align-self-start mb-2">{{ $article->category->name }}</span>
                            <h2 class="h5 card-title">
                                <a href="{{ route('articles.show', $article->permalink) }}" class="stretched-link text-decoration-none text-dark">{{ $article->title }}</a>
                            </h2>
                            <p class="card-text text-secondary">{{ $article->summary }}</p>
                            <p class="card-text mt-auto mb-0">
                                <small class="text-body-secondary">
                                    <time datetime="{{ $article->published_at->toDateString() }}">{{ $article->published_at->format('j M Y') }}</time>
                                </small>
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <p id="no-results" class="text-center text-secondary mt-4" hidden>No articles match your search.</p>
    @endif
@endsection

@push('scripts')
    @if (! $articles->isEmpty())
        <script>
            const searchInput = document.getElementById('article-search');
            const articleItems = document.querySelectorAll('.article-item');
            const noResults = document.getElementById('no-results');

            // Runs on every keystroke: show only the cards whose title contains the typed text.
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.trim().toLowerCase();
                let visibleCount = 0;

                articleItems.forEach((item) => {
                    // An empty query matches every title, so clearing the box shows all articles again.
                    const matches = item.dataset.title.toLowerCase().includes(query);
                    item.hidden = !matches;

                    if (matches) {
                        visibleCount++;
                    }
                });

                noResults.hidden = visibleCount > 0;
            });
        </script>
    @endif
@endpush
