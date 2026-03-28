<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;

class EnrollmentControllerTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_etudiant_peut_sinscrire_a_un_cours()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $course = Course::factory()->create(['price' => 50]);

        // On mocke le service pour simuler un paiement réussi
        $this->mock(EnrollmentService::class, function (MockInterface $mock) use ($course) {
            $mock->shouldReceive('enrollStudent')
                ->once()
                ->with((string)$course->id, 'tok_visa')
                ->andReturn(['id' => 1, 'course_id' => $course->id]);
        });

        $response = $this->actingAs($student, 'api')
            ->postJson("/api/courses/{$course->id}/enroll", [
                'stripeToken' => 'tok_visa'
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('message', 'Inscription réussie !');
    }

    
    public function test_enseignant_peut_voir_ses_statistiques()
    {
        $teacher = User::factory()->create(['role' => 'enseignant']);

        $this->mock(EnrollmentService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getStatsForTeacher')
                ->once()
                ->andReturn([
                    'total_revenue' => 150.00,
                    'total_enrollments' => 3
                ]);
        });

        $response = $this->actingAs($teacher, 'api')
            ->getJson('/api/my-stats');

        $response->assertStatus(200)
                 ->assertJson([
                     'total_revenue' => 150.00,
                     'total_enrollments' => 3
                 ]);
    }

    
    public function test_etudiant_peut_se_retirer_dun_cours()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $course = Course::factory()->create();

        $this->mock(EnrollmentService::class, function (MockInterface $mock) use ($course) {
            $mock->shouldReceive('cancelEnrollment')
                ->once()
                ->with((string)$course->id)
                ->andReturn(true);
        });

        $response = $this->actingAs($student, 'api')
            ->deleteJson("/api/courses/{$course->id}/withdraw");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Vous vous êtes retiré du cours avec succès.');
    }

   
    public function test_erreur_si_retrait_dun_cours_non_inscrit()
    {
        $student = User::factory()->create(['role' => 'étudiant']);
        $course = Course::factory()->create();

        $this->mock(EnrollmentService::class, function (MockInterface $mock) use ($course) {
            $mock->shouldReceive('cancelEnrollment')->andReturn(false);
        });

        $response = $this->actingAs($student, 'api')
            ->deleteJson("/api/courses/{$course->id}/withdraw");

        $response->assertStatus(404)
                 ->assertJsonPath('message', 'Inscription introuvable.');
    }
}