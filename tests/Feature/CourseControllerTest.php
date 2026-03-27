<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Services\CourseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de récupération de la liste des cours.
     */
    public function test_peut_lister_les_cours()
    {
        $this->mock(CourseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAllCourses')->once()->andReturn([]);
        });

        // Simule un utilisateur connecté (car tes routes sont sous auth:api)
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/courses');

        $response->assertStatus(200);
    }

    /**
     * Test de création d'un cours par un Enseignant.
     */
    public function test_enseignant_peut_creer_un_cours()
    {
        $teacher = User::factory()->create(['role' => 'enseignant']);
        $category = Category::factory()->create();

        $this->mock(CourseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('createCourse')->once()->andReturn(['id' => 1, 'title' => 'Laravel']);
        });

        $response = $this->actingAs($teacher, 'api')->postJson('/api/courses', [
            'title' => 'Laravel Avancé',
            'description' => 'Un cours sur le Repository Pattern',
            'price' => 49.99,
            'category_id' => $category->id
        ]);

        $response->assertStatus(201);
    }

    /**
     * Test de sécurité : Un étudiant ne peut pas créer de cours.
     */
    public function test_etudiant_ne_peut_pas_creer_un_cours()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $category = Category::factory()->create();

        // Note : Ici le middleware 'role:enseignant' de tes routes devrait bloquer avant même le contrôleur
        $response = $this->actingAs($student, 'api')->postJson('/api/courses', [
            'title' => 'Tentative de piratage',
            'description' => '...',
            'price' => 10,
            'category_id' => $category->id
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test des Favoris (Toggle).
     */
    public function test_etudiant_peut_ajouter_un_favori()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $course = Course::factory()->create();

        $this->mock(CourseService::class, function (MockInterface $mock) use ($course) {
            $mock->shouldReceive('toggleFavorite')
                ->once()
                ->with($course->id)
                ->andReturn(['attached' => [1]]); // Simule l'ajout
        });

        $response = $this->actingAs($student, 'api')
            ->postJson("/api/courses/{$course->id}/favorite");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Cours ajouté aux favoris.']);
    }

    /**
     * Test des Recommandations.
     */
    public function test_peut_obtenir_des_recommandations()
    {
        $student = User::factory()->create(['role' => 'étudiant']);

        $this->mock(CourseService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getRecommendations')->once()->andReturn([]);
        });

        $response = $this->actingAs($student, 'api')->getJson('/api/recommendations');

        $response->assertStatus(200);
    }
}