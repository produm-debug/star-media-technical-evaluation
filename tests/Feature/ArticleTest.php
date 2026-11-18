<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

// The "homepage returns 200" case is already covered by tests/Feature/ExampleTest.php,
// so the tests below only add what that test doesn't check.

it('shows published articles on the homepage', function () {
    $category = Category::factory()->create(['name' => 'Technology']);
    Article::factory()->for($category)->create([
        'title' => 'Chip makers expand production',
        'summary' => 'Factories are opening across the region.',
        'permalink' => 'chip-makers-expand-production',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Chip makers expand production')
        ->assertSee('Factories are opening across the region.')
        ->assertSee('Technology')
        ->assertSee('/articles/chip-makers-expand-production', false);
});

it('does not show draft articles on the homepage', function () {
    Article::factory()->create(['title' => 'Published headline']);
    Article::factory()->create(['title' => 'Secret draft headline', 'published_at' => null]);

    $this->get('/')
        ->assertOk()
        ->assertSee('Published headline')
        ->assertDontSee('Secret draft headline');
});

it('loads homepage categories without N+1 queries', function () {
    // Each article gets its own category, so lazy loading would need one extra query per article.
    Article::factory(5)->create();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->get('/')->assertOk();

    // One query for the articles plus one for their categories.
    expect(count(DB::getQueryLog()))->toBeLessThanOrEqual(2);
});

it('shows the full article on its detail page', function () {
    $category = Category::factory()->create(['name' => 'Business']);
    Article::factory()->for($category)->create([
        'title' => 'Markets rally after rate decision',
        'permalink' => 'markets-rally-after-rate-decision',
        'body' => "First paragraph of the story.\n\nSecond paragraph of the story.",
        'published_at' => '2026-03-15 09:30:00',
    ]);

    $this->get('/articles/markets-rally-after-rate-decision')
        ->assertOk()
        ->assertSee('Markets rally after rate decision')
        ->assertSee('First paragraph of the story.')
        ->assertSee('Second paragraph of the story.')
        ->assertSee('Business')
        ->assertSee('15 March 2026');
});

it('returns 404 for an unknown permalink', function () {
    $this->get('/articles/does-not-exist')->assertNotFound();
});

it('returns 404 for the permalink of a draft article', function () {
    $draft = Article::factory()->create(['published_at' => null]);

    $this->get('/articles/'.$draft->permalink)->assertNotFound();
});
