<?php
namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:api')->except(['login', 'register']);
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        
        return response()->json([
            'message' => 'User successfully registered',
            'user' => new UserResource($result['user']),
            'token' => $result['token']
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request->validated());
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}