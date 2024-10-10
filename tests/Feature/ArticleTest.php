<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up a user and token for authenticated requests
        $this->user = User::factory()->create();

        $this->token = 'Bearer ' . $this->user->createToken('auth_token')->plainTextToken;

        // Create some test data for articles, categories, authors, and sources
        Article::factory()->count(10)->create();
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_fetching_articles()
    {
        $response = $this->json('GET', '/api/articles');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status' => [
                        'code',
                        'message',
                        'error',
                        'validation_errors',
                    ],
                    'data'
                ]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_fetching_paginated_articles()
    {
        $response = $this->json('GET', '/api/articles');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'current_page',
                        'data',
                        'first_page_url',
                        'from',
                        'last_page',
                        'last_page_url',
                        'next_page_url',
                        'path',
                        'per_page',
                        'prev_page_url',
                        'to',
                        'total'
                    ]
                ]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_filter_articles_by_category()
    {
        $category = Category::factory()->create(['name' => 'Technology']);
        Article::factory()->create(['category_id' => $category->id]);

        $response = $this->json('GET', '/api/articles?category=Technology');
        $response->assertStatus(200)
                 ->assertJsonFragment(['category_id' => $category->id]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_filter_articles_by_author()
    {
        $author = Author::factory()->create(['name' => 'Mustafa Ahmed']);
        $article = Article::factory()->create();
        $article->authors()->attach($author);

        $response = $this->json('GET', '/api/articles?author=Mustafa Ahmed');

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Mustafa Ahmed']);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_filter_articles_by_source()
    {
        $source = Source::factory()->create(['name' => 'BBC']);
        Article::factory()->create(['source_id' => $source->id]);

        $response = $this->getJson('/api/articles?source=BBC');

        $response->assertStatus(200)
                 ->assertJsonFragment(['source_id' => $source->id]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_apply_user_preferred_sources()
    {
        // Add some preferred sources for the user
        $source = Source::factory()->create(['name' => 'BBC']);
        Article::factory()->count(5)->create(['source_id' => $source->id]);

        $this->user->preferences()->create(
            ['key' => 'sources', 'value' => ['BBC']]
        );

        $response = $this->json('GET', '/api/articles', [], ['Authorization' => $this->token]);
        $response->assertStatus(200)
                 ->assertJsonFragment(['source_id' => $source->id]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_apply_user_preferred_categories()
    {
        // Add some preferred categories for the user
        $category = Category::factory()->create(['name' => 'Technology']);
        Article::factory()->count(5)->create(['category_id' => $category->id]);

        $this->user->preferences()->create(
            ['key' => 'categories', 'value' => ['Technology']]
        );

        $response = $this->json('GET', '/api/articles', [], ['Authorization' => $this->token]);
        $response->assertStatus(200)
                 ->assertJsonFragment(['category_id' => $category->id]);
    }
}
