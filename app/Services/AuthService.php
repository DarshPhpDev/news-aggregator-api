<?php

namespace App\Services;

use App\Models\User;
use Auth;

class AuthService
{
    protected $model;

    /**
     * AuthService constructor.
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function createUser($validatedData)
    {
        return $this->model->create([
            'name'      => $validatedData['name'],
            'email'     => $validatedData['email'],
            'password'  => bcrypt($validatedData['password']),
        ]);
    }

    public function createUserToken($user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function login($request)
    {
        // Using Auth::guard('web') since our current api guard using sanctum which doesn't have attempt method.
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            return false;
        }

        return $this->model->where('email', $request['email'])->first();        
    }

    public function loggedOut()
    {
        if($user = Auth::user()){
            $user->tokens()->delete();
            return true;
        }
        return false;
    }

}