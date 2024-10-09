<?php

namespace App\Http\Controllers\Api;

use ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Auth;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Authentication", description="User authentication apis")
 */

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     description="Register a new user and return an access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", description="Access token for the registered user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to register user"
     *     )
     * )
     */

    public function register(RegisterRequest $request) {
        $validatedData = $request->validated();
        
        if($user = $this->authService->createUser($validatedData)){
            $token = $this->authService->createUserToken($user);
            return ApiResponse::sendResponse([
                'access_token' => $token,
            ], 200);
        }

        return ApiResponse::sendResponse([], 500, 'Failed to register user.');
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     description="Login a user and return an access token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", description="Access token for the authenticated user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid login credentials"
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        if($user = $this->authService->login($request)){
            $token = $this->authService->createUserToken($user);
            return ApiResponse::sendResponse([
                'access_token' => $token,
            ], 200);
        }
        return ApiResponse::sendResponse([], 401, 'Invalid login credentials', true);
    }


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the authenticated user",
     *     description="Logout the current user by revoking the access token",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Not authenticated or already logged out!"
     *     )
     * )
     */

    public function logout()
    {
        if($this->authService->loggedOut()){
            return ApiResponse::sendResponse([], 200, 'Logged out!');
        }

        return ApiResponse::sendResponse([], 401, 'Not authenticated or already logged out!');
    }
}
