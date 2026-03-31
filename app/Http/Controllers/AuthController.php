<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Authentification", description="Gestion des comptes utilisateurs, connexion et mot de passe")
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
     * path="/api/register",
     * summary="Inscription d'un nouvel utilisateur",
     * tags={"Authentification"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name","email","password","password_confirmation","role"},
     * @OA\Property(property="name", type="string", example="Jean Dupont"),
     * @OA\Property(property="email", type="string", format="email", example="jean@test.com"),
     * @OA\Property(property="password", type="string", format="password", example="password123"),
     * @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     * @OA\Property(property="role", type="string", enum={"étudiant", "enseignant"}, example="étudiant"),
     * @OA\Property(property="interests", type="array", @OA\Items(type="string"), example={"Web", "Design"})
     * )
     * ),
     * @OA\Response(response=201, description="Utilisateur créé avec succès"),
     * @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:étudiant,enseignant',
            'interests' => 'nullable|array'
        ]);

        $user = $this->authService->register($data);
        return response()->json(['message' => 'Utilisateur créé', 'user' => $user], 201);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Connexion utilisateur",
     * tags={"Authentification"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email","password"},
     * @OA\Property(property="email", type="string", format="email", example="jean@test.com"),
     * @OA\Property(property="password", type="string", format="password", example="password123")
     * )
     * ),
     * @OA\Response(response=200, description="Connexion réussie, retourne le token"),
     * @OA\Response(response=401, description="Identifiants invalides")
     * )
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    try {
        $token = $this->authService->login($credentials);
        
        // On récupère l'utilisateur authentifié (via le guard API)
        $user = auth('api')->user(); 

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'role' => $user->role, // Assure-toi que la colonne 'role' existe en base
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 401);
    }
}

    /**
     * @OA\Post(
     * path="/api/forgot-password",
     * summary="Demander un lien de réinitialisation de mot de passe",
     * tags={"Authentification"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email"},
     * @OA\Property(property="email", type="string", format="email", example="jean@test.com")
     * )
     * ),
     * @OA\Response(response=200, description="Lien envoyé"),
     * @OA\Response(response=400, description="Erreur lors de l'envoi")
     * )
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $message = $this->authService->sendResetLink($request->only('email'));
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Post(
     * path="/api/reset-password",
     * summary="Réinitialiser le mot de passe avec un token",
     * tags={"Authentification"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"token","email","password","password_confirmation"},
     * @OA\Property(property="token", type="string", example="abcdef123456"),
     * @OA\Property(property="email", type="string", format="email", example="jean@test.com"),
     * @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     * @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     * )
     * ),
     * @OA\Response(response=200, description="Mot de passe réinitialisé"),
     * @OA\Response(response=400, description="Token invalide ou erreur")
     * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $message = $this->authService->resetPassword($request->all());
            return response()->json(['message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}