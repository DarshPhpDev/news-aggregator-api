<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setup();

        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->token = 'Bearer ' . $user->createToken('auth_token')->plainTextToken;
    }


    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_if_user_can_register()
    {
        $response = $this->json(
                        'POST', 
                        '/api/register', 
                        [
                            'name' => 'John Doe 2',
                            'email' => 'john@example2.com',
                            'password' => 'password123',
                        ], 
                        []
                    )->assertStatus(200)
                    ->assertJsonStructure([
                        'status' => [
                            'code',
                            'message',
                            'error',
                            'validation_errors',
                        ],
                        'data' => [
                            'access_token',
                        ]
                    ]);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_validation_error_when_missing_required_registration_fields()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // If name is missing
        $response = $this->json(
                        'POST', 
                        '/api/register', 
                        [
                            'email' => 'john@example2.com',
                            'password' => 'password123'
                        ], 
                        []
                    )->assertStatus(500);

        // If email is missing
        $response = $this->json(
                        'POST', 
                        '/api/register', 
                        [
                            'name' => 'John Doe',
                            'password' => 'password123'
                        ], 
                        []
                    )->assertStatus(500);

        // If password is missing
        $response = $this->json(
                        'POST', 
                        '/api/register', 
                        [
                            'name' => 'John Doe',
                            'email' => 'john@example2.com'
                        ], 
                        []
                    )->assertStatus(500);

    }


    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_if_user_can_login_with_correct_credentials()
    {
        $response = $this->json(
                        'POST', 
                        '/api/login', 
                        [
                            'email' => 'john@example.com',
                            'password' => 'password123',
                        ], 
                        []
                    )->assertStatus(200)
                    ->assertJsonStructure([
                        'status' => [
                            'code',
                            'message',
                            'error',
                            'validation_errors',
                        ],
                        'data' => [
                            'access_token',
                        ]
                    ]);
    }


    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_login_validation_when_missing_email_or_password()
    {
        $this->withoutExceptionHandling();
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        // If email is missing
        $response = $this->json(
                        'POST', 
                        '/api/login', 
                        [
                            'password' => 'password123'
                        ], 
                        []
                    )->assertStatus(500);

        // If password is missing
        $response = $this->json(
                        'POST', 
                        '/api/login', 
                        [
                            'email' => 'john@example2.com'
                        ], 
                        []
                    )->assertStatus(500);
    }

    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_if_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->json(
                        'POST', 
                        '/api/login', 
                        [
                            'email' => 'john@example.com',
                            'password' => 'password',
                        ], 
                        []
                    )->assertStatus(401)
                    ->assertJson([
                        'status' => [
                            'code'                  => 401,
                            'message'               => 'Invalid login credentials',
                            'error'                 => true,
                            'validation_errors'     => []
                        ]
                    ]);
    }
    
    /**
     * @author Mustafa Ahmed <mustafa.softcode@gmail.com>
     */
    public function test_if_user_can_logout()
    {
        $response = $this->json(
                        'POST', 
                        '/api/logout', 
                        [], 
                        ['Authorization' => $this->token]
                    )->assertStatus(200)
                    ->assertJson([
                        'status' => [
                            'code'                  => 200,
                            'message'               => 'Logged out!',
                            'error'                 => false,
                            'validation_errors'     => []
                        ]
                    ]);
    }
}
