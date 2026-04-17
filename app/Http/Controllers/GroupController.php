<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Groupes", description="Gestion des groupes automatiques pour les cours")
 */
class GroupController extends Controller
{
    protected $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    /**
     * @OA\Get(
     * path="/api/courses/{courseId}/groups",
     * summary="Récupérer la structure des groupes d'un cours",
     * description="Affiche la répartition des étudiants par groupes (max 25 par groupe). Réservé aux enseignants.",
     * tags={"Groupes"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="courseId",
     * in="path",
     * required=true,
     * description="L'ID du cours pour lequel on veut voir les groupes",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Structure des groupes récupérée avec succès",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="course_id", type="integer", example=1),
     * @OA\Property(
     * property="data", 
     * type="array", 
     * @OA\Items(
     * type="object",
     * @OA\Property(property="group_name", type="string", example="Groupe A"),
     * @OA\Property(property="student_count", type="integer", example=25),
     * @OA\Property(property="students", type="array", @OA\Items(type="object"))
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=403,
     * description="Accès interdit ou erreur lors de la récupération",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="Action non autorisée.")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Utilisateur non authentifié"
     * )
     * )
     */
    public function index($courseId)
    {
        try {
            $groups = $this->enrollmentService->getGroupsInfo($courseId);

            return response()->json([
                'status' => 'success',
                'course_id' => $courseId,
                'data' => $groups
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 403);
        }
    }

    public function teacherStudents()
    {
        try {
            $groups = $this->enrollmentService->getTeacherGroupsWithStudents();
            return response()->json(['status' => 'success', 'data' => $groups], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}