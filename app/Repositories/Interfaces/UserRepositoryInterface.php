<?php
namespace App\Repositories\Interfaces;

interface UserRepositoryInterface {
    public function create(array $data);
    public function findByEmail(string $email);
    public function updatePassword(string $email, string $newPassword);
}