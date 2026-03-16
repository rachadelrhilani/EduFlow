<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(Request $request) {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:étudiant,enseignant', // Validation du rôle
            'interests' => 'nullable|array'
        ]);

        $user = $this->authService->register($data);
        return response()->json(['message' => 'Utilisateur créé', 'user' => $user], 201);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        try {
            $token = $this->authService->login($credentials);
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
