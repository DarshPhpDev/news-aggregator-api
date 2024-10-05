<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use ApiResponse;

class AuthController extends Controller
{
    /**
     * Register new User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return ApiResponse::sendResponse([
            'access_token' => $token,
        ], 200);
    }

    /**
     * Login User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Using Auth::guard('web') since our current api guard using sanctum which doesn't have attempt method.
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return ApiResponse::sendResponse([], 401, 'Invalid login credentials');
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::sendResponse([
            'access_token' => $token,
        ], 200);
    }

    /**
     * Logout User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if($user = Auth::user()){
            $user->tokens()->delete();
            return ApiResponse::sendResponse([], 200, 'Logged out!');
        }else{
            return ApiResponse::sendResponse([], 200, 'Not authenticated or already logged out!');
        }
    }
}
