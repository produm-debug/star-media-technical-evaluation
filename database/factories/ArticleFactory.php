<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(6), '.');

        return [
            'title' => $title,
            'summary' => fake()->text(200),
            'body' => implode("\n\n", fake()->paragraphs(5)),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            // The unique numeric suffix keeps permalinks distinct even if two titles produce the same slug.
            'permalink' => Str::slug($title).'-'.fake()->unique()->numberBetween(1000, 999999),
            // Creates a new category unless ->for($category) or an explicit category_id overrides it.
            'category_id' => Category::factory(),
        ];
    }
}
