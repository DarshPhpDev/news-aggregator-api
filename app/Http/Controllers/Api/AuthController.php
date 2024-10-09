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

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register new User
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
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
     * Login User
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        if($user = $this->authService->login($request->only('email', 'password'))){
            $token = $this->authService->createUserToken($user);
            return ApiResponse::sendResponse([
                'access_token' => $token,
            ], 200);
        }
        return ApiResponse::sendResponse([], 401, 'Invalid login credentials');
    }

    /**
     * Logout User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if($this->authService->loggedOut()){
            return ApiResponse::sendResponse([], 200, 'Logged out!');
        }

        return ApiResponse::sendResponse([], 200, 'Not authenticated or already logged out!');
    }
}
