<?php
namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthService {
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo) {
        $this->userRepo = $userRepo;
    }

    public function register(array $data) {
        return $this->userRepo->create($data);
    }

    public function login(array $credentials) {
        if (!$token = auth('api')->attempt($credentials)) {
            throw new Exception("Identifiants invalides");
        }
        return $token;
    }
}