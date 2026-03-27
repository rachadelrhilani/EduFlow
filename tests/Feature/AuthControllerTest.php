<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_inscription_reussie()
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('register')
                ->once()
                ->andReturn(['name' => 'Jean Dupont', 'email' => 'jean@test.com']);
        });

        $response = $this->postJson('/api/register', [
            'name' => 'Jean Dupont',
            'email' => 'jean@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'étudiant',
            'interests' => ['Web', 'Laravel']
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Utilisateur créé');
    }

    public function test_inscription_echoue_si_donnees_invalides()
    {
        $response = $this->postJson('/api/register', [
            'name' => '', // Nom vide
            'email' => 'pas-un-email',
            'password' => 'short', // trop court
            'role' => 'admin' // rôle non autorisé
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    }


    public function test_connexion_genere_un_token()
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('login')
                ->once()
                ->with(['email' => 'jean@test.com', 'password' => 'password123'])
                ->andReturn('fake-jwt-token');
        });

        $response = $this->postJson('/api/login', [
            'email' => 'jean@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    }


    public function test_demande_lien_reinitialisation()
    {
        $this->mock(AuthService::class, function (MockInterface $mock) {
            $mock->shouldReceive('sendResetLink')
                ->once()
                ->andReturn('Lien envoyé');
        });

        $response = $this->postJson('/api/password/forgot', [
            'email' => 'jean@test.com'
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Lien envoyé']);
    }
}