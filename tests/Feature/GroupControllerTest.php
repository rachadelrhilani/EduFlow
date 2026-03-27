<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test qu'un enseignant peut voir les groupes d'un cours.
     */
    public function test_enseignant_peut_consulter_les_groupes()
    {
        $teacher = User::factory()->create(['role' => 'enseignant']);
        $course = Course::factory()->create();

        // On simule le retour du service avec des données factices
        $fakeGroups = [
            [
                'group_name' => 'Groupe A',
                'student_count' => 2,
                'students' => [['id' => 1, 'name' => 'Alice'], ['id' => 2, 'name' => 'Bob']]
            ]
        ];

        $this->mock(EnrollmentService::class, function (MockInterface $mock) use ($course, $fakeGroups) {
            $mock->shouldReceive('getGroupsInfo')
                ->once()
                ->with((string)$course->id)
                ->andReturn($fakeGroups);
        });

        $response = $this->actingAs($teacher, 'api')
            ->getJson("/api/courses/{$course->id}/groups");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'course_id' => $course->id,
                     'data' => $fakeGroups
                 ]);
    }

    /**
     * Test de sécurité : un étudiant ne doit pas accéder aux groupes.
     */
    public function test_etudiant_ne_peut_pas_consulter_les_groupes()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $course = Course::factory()->create();

        // Pas besoin de mocker le service ici, car le middleware doit bloquer avant
        $response = $this->actingAs($student, 'api')
            ->getJson("/api/courses/{$course->id}/groups");

        // On attend une erreur 403 (Forbidden) via ton middleware role:enseignant
        $response->assertStatus(403);
    }

    /**
     * Test de gestion d'erreur (Exception levée par le service).
     */
    public function test_erreur_si_le_service_echoue()
    {
        $teacher = User::factory()->create(['role' => 'enseignant']);
        $course = Course::factory()->create();

        $this->mock(EnrollmentService::class, function (MockInterface $mock) use ($course) {
            $mock->shouldReceive('getGroupsInfo')
                ->andThrow(new \Exception("Erreur de base de données"));
        });

        $response = $this->actingAs($teacher, 'api')
            ->getJson("/api/courses/{$course->id}/groups");

        $response->assertStatus(403)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Erreur de base de données'
                 ]);
    }
}