<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

it('returns published articles with their category as JSON', function () {
    $category = Category::factory()->create(['name' => 'Technology']);
    Article::factory()->for($category)->create(['title' => 'Chip makers expand production']);

    $this->getJson('/api/articles')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonStructure([
            'data' => [
                ['id', 'title', 'summary', 'body', 'permalink', 'published_at', 'category' => ['id', 'name']],
            ],
        ])
        ->assertJsonPath('data.0.title', 'Chip makers expand production')
        ->assertJsonPath('data.0.category.id', $category->id)
        ->assertJsonPath('data.0.category.name', 'Technology');
});

it('excludes draft articles from the API', function () {
    Article::factory()->create(['title' => 'Published headline']);
    Article::factory()->create(['title' => 'Secret draft headline', 'published_at' => null]);

    $this->getJson('/api/articles')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonMissing(['title' => 'Secret draft headline']);
});

it('lists the newest articles first', function () {
    Article::factory()->create(['title' => 'Older story', 'published_at' => '2026-01-01 10:00:00']);
    Article::factory()->create(['title' => 'Newer story', 'published_at' => '2026-06-01 10:00:00']);

    $this->getJson('/api/articles')
        ->assertOk()
        ->assertJsonPath('data.0.title', 'Newer story')
        ->assertJsonPath('data.1.title', 'Older story');
});

it('loads API categories without N+1 queries', function () {
    // Each article gets its own category, so lazy loading would need one extra query per article.
    Article::factory(5)->create();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->getJson('/api/articles')->assertOk();

    // One query for the articles plus one for their categories.
    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(2);
});
