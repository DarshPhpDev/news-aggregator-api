<?php
namespace Tests\Unit;

use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use App\Models\Source;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Set up a user and token for authenticated requests
        $this->user = User::factory()->create();

        $this->token = 'Bearer ' . $this->user->createToken('auth_token')->plainTextToken;
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_get_user_preferrences()
    {
        // Add some preferred categories/sources/authors for the user
        $this->user->preferences()->create(
            ['key' => 'categories', 'value' => ['Technology', 'Politics']]
        );

        $this->user->preferences()->create(
            ['key' => 'sources', 'value' => ['BBC', 'CNN']]
        );

        $this->user->preferences()->create(
            ['key' => 'authors', 'value' => ['Mustafa Ahmed', 'John Doe']]
        );

        $response = $this->json('GET', '/api/preferences', [], ['Authorization' => $this->token]);
        $response->assertStatus(200)
                 ->assertJson([
                        'status' => [
                            'code'              => 200,
                            'message'           => 'success',
                            'error'             => false,
                            'validation_errors' => [],
                        ],
                        'data' => [
                            'categories'    => ['Technology','Politics'],
                            'sources'       => ['BBC', 'CNN'],
                            'authors'       => ['Mustafa Ahmed', 'John Doe'],
                        ]
                    ]);
    }


    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_set_user_preferrences()
    {
        // Add some preferred categories/sources/authors for the user
        $response = $this->json('POST', '/api/preferences', 
                                [
                                    "sources" => [
                                        "BBC",
                                        "CNN"
                                    ],
                                    "authors" => [
                                        "Mustafa Ahmed",
                                        "John Doe"
                                    ],
                                    "categories" => [
                                        "Technology",
                                        "Politics"
                                    ]
                                ], 
                                ['Authorization' => $this->token]);

        $userPreferences = $this->user->preferences()->pluck('value', 'key')->toArray();

        $response->assertStatus(200);

        $this->assertEquals(['BBC', 'CNN'], $userPreferences['sources']);
        $this->assertEquals(['Mustafa Ahmed', 'John Doe'], $userPreferences['authors']);
        $this->assertEquals(['Technology', 'Politics'], $userPreferences['categories']);
    }
}
