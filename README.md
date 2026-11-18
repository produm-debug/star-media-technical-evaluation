# Candidate Technical Evaluation - Star Media Group

## Time Limit: 60 - 90 Minutes

### Prerequisites
- PHP 8.5+ installed
- Composer 2.x installed

### Quick Setup Instructions
1. Clone this repository to your local machine.
2. Run `composer install`
3. Copy the environment file: `cp .env.example .env`
   - `.env.example` is already configured for SQLite (`DB_CONNECTION=sqlite`), so no database settings need to be changed. The database file defaults to `database/database.sqlite`.
4. Generate app key: `php artisan key:generate`
5. Create the SQLite database file if it does not exist yet: `touch database/database.sqlite`
6. Run the migrations and seed the sample data (5 categories and 20 articles): `php artisan migrate --seed`
7. Start dev server: `php artisan serve`
8. Open http://localhost:8000 in your browser.

> The seeder is not safe to run twice on the same database (it fails on the unique user email and category names). To start again from a clean database, run `php artisan migrate:fresh --seed`. This deletes all existing data in the database.

### Running the Tests
```bash
php artisan test
```
The tests use an in-memory SQLite database (configured in `phpunit.xml`), so they do not read or change `database/database.sqlite`.

### Pages and API Endpoints
| Method | URL | Description |
|--------|-----|-------------|
| GET | `/` | Article listing (title, summary, category, published date) with a real-time title search box. Each article links to its detail page. |
| GET | `/articles/{permalink}` | Article detail page (full title, body, category, published date) with a link back to the listing. Returns a 404 page for an unknown permalink. |
| GET | `/api/articles` | JSON list of articles, newest first, each with its category (loaded with `with('category')` to avoid N+1 queries). |

Example `GET /api/articles` response (shape only, values will differ):
```json
{
  "data": [
    {
      "id": 1,
      "title": "Example article title",
      "summary": "Short summary text.",
      "body": "Full article body.",
      "permalink": "example-article-title-12345",
      "published_at": "2026-11-02T08:21:33.000000Z",
      "category_id": 3,
      "category": { "id": 3, "name": "Technology" }
    }
  ]
}
```
Only articles with a `published_at` value are shown on the website and returned by the API. Articles with a null `published_at` are treated as drafts. This is a design assumption, not a stated requirement, so a draft's permalink returns a 404.

### Tasks
#### 1. **Migrations & Models:** Create the Article and Category models with standard Eloquent relationships (a Category has many Articles).
- `Category` Model Fields:
  - `id` (primary key)
  - `name` (string)
  - `Article` Model Fields:
    - `title` (string)
    - `summary` (string)
    - `body` (text)
    - `published_at` (timestamp, nullable)
    - `permalink` (string)
    - `category_id` (foreign key referencing `categories`)
    - _(Optional)_: Add 1 or 2 extra properties you feel are essential for a news portal article (e.g., `status`, `thumbnail_url`, `author`).
#### 2. **Factories & Database Seeding**
- Create an `ArticleFactory` using Faker for titles, summary, and body content.
- Configure `DatabaseSeeder.php` so running `php artisan db:seed` automatically populates the database with at least 5 categories and 20 articles.
#### 3. **Controller & API Endpoint:** Create a GET controller method returning articles formatted as JSON. Prevent N+1 database queries using eager loading (`with`).
#### 4. **Frontend Integration:**
- **Article List View:** Render a list of articles using standard Blade views styled with Bootstrap (or Livewire).
- **Search Filter:** Implement a simple real-time JavaScript (or Livewire) search filter to filter articles by title on the list page.
- **Article Detail Page:** Create a single article view page that displays the full title, body, category, and published date when an article from the list is clicked.

### Submission Guidelines
- Commit code cleanly with clear commit messages.
- Push your completed work back to your repository and share the repository link with us.