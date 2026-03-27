<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\EnrollmentService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Inscriptions", description="Gestion des inscriptions aux cours et statistiques")
 */
class EnrollmentController extends Controller
{
    protected $enrollService;

    public function __construct(EnrollmentService $enrollService)
    {
        $this->enrollService = $enrollService;
    }

    /**
     * @OA\Post(
     * path="/api/courses/{id}/enroll",
     * summary="S'inscrire à un cours (Paiement Stripe)",
     * tags={"Inscriptions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID du cours",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"stripeToken"},
     * @OA\Property(property="stripeToken", type="string", example="tok_visa", description="Token généré par Stripe.js")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Inscription réussie",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Inscription réussie !"),
     * @OA\Property(property="data", type="object")
     * )
     * ),
     * @OA\Response(response=400, description="Erreur de paiement ou d'inscription"),
     * @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function store(Request $request, $id)
    {
        $request->validate(['stripeToken' => 'required']);

        try {
            $enrollment = $this->enrollService->enrollStudent($id, $request->stripeToken);
            return response()->json([
                'message' => 'Inscription réussie !',
                'data' => $enrollment
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     * path="/api/my-courses/{id}/students",
     * summary="Liste des étudiants inscrits à un cours (Enseignant uniquement)",
     * tags={"Inscriptions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Liste des étudiants récupérée",
     * @OA\JsonContent(
     * @OA\Property(property="course_id", type="integer"),
     * @OA\Property(property="total_students", type="integer"),
     * @OA\Property(property="students", type="array", @OA\Items(type="object"))
     * )
     * ),
     * @OA\Response(response=403, description="Accès refusé")
     * )
     */
    public function courseStudents($id)
    {
        try {
            $students = $this->enrollService->getStudentsForCourse($id);
            return response()->json([
                'course_id' => $id,
                'total_students' => $students->count(),
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * @OA\Get(
     * path="/api/my-stats",
     * summary="Statistiques globales des revenus et inscriptions (Enseignant uniquement)",
     * tags={"Inscriptions"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200, 
     * description="Statistiques récupérées",
     * @OA\JsonContent(
     * @OA\Property(property="total_revenue", type="number", format="float"),
     * @OA\Property(property="total_enrollments", type="integer")
     * )
     * )
     * )
     */
    public function stats()
    {
        $stats = $this->enrollService->getStatsForTeacher();
        return response()->json($stats);
    }

    /**
     * @OA\Delete(
     * path="/api/courses/{id}/withdraw",
     * summary="Se désinscrire d'un cours",
     * tags={"Inscriptions"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200, 
     * description="Désinscription réussie",
     * @OA\JsonContent(@OA\Property(property="message", type="string"))
     * ),
     * @OA\Response(response=404, description="Inscription introuvable")
     * )
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->enrollService->cancelEnrollment($id);

            if ($deleted) {
                return response()->json([
                    'message' => 'Vous vous êtes retiré du cours avec succès.'
                ], 200);
            }

            return response()->json([
                'message' => 'Inscription introuvable.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}