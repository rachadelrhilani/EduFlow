<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(array $data)
    {
        return $this->userRepo->create($data);
    }

    public function login(array $credentials)
    {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new Exception("Identifiants invalides");
        }
        return $token;
    }
    public function sendResetLink(array $email)
    {
        // Envoie l'email via le système natif (configuré dans .env)
        $status = Password::sendResetLink($email);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception(__($status)); // Retourne l'erreur (ex: utilisateur introuvable)
        }
        return __($status);
    }

    public function resetPassword(array $data)
    {
        // Valide le token et change le mot de passe
        $status = Password::reset($data, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw new \Exception(__($status));
        }
        return __($status);
    }
}
