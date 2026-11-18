<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 5 categories x 4 articles each = 20 articles.
        foreach (['Politics', 'Business', 'Technology', 'Sports', 'Entertainment'] as $name) {
            $category = Category::create(['name' => $name]);

            Article::factory(4)->for($category)->create();
        }
    }
}
