<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //Register
    public function store(AuthRequest $request): JsonResponse
    {
        User::create($request->all());
        return $this->ResponseJson(true, null, 'User Created Successfully', null, 201);
    }
    //Register Admin
    public function storeAdmin(AuthRequest $request): JsonResponse
    {
        $reqData = $request->all();
        User::create(['role' => 'admin', ...$reqData]);
        return $this->ResponseJson(true, null, 'User Created Successfully', null, 201);
    }
    //Login
    public function login(AuthRequest $request): JsonResponse
    {

        // Get the user from the database
        $user = User::where('email', $request->email)->first();



        if (!$user || ! Hash::check($request->password, $user->password)) {
            return $this->ResponseJson(false, null, 'Invaid email or password', null, 400);
        }

        $token = $user->createToken('movie-reservation-system-api')->plainTextToken;

        return $this->ResponseJson(true, ["token" => $token], 'User Loggedin Successfully', null, 200);
    }

    // profile
    public function profile(): JsonResponse
    {
        $userData = request()->user();
        return $this->ResponseJson(true, new UserResource($userData), 'User Profile found', null, 200);
    }

    // Logout
    public function logout(): JsonResponse
    {
        $userData = request()->user();
        $userData->tokens()->delete();
        return $this->ResponseJson(true, null, 'User Logged out Successfully', null, 200);
    }
}
