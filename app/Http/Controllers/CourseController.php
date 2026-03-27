<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Cours", description="Gestion des cours, favoris et recommandations")
 */
class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * @OA\Get(
     * path="/api/courses",
     * summary="Liste de tous les cours disponibles",
     * tags={"Cours"},
     * * security={{"bearerAuth":{}}}, 
     * @OA\Response(response=200, description="Liste des cours récupérée"),
     * )
     */
    
    public function index()
    {
        return response()->json($this->courseService->getAllCourses());
    }

    /**
     * @OA\Get(
     * path="api/courses/{id}",
     * summary="Détails d'un cours spécifique",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="Détails du cours"),
     * @OA\Response(response=404, description="Cours non trouvé")
     * )
     */
    public function show($id)
    {
        return response()->json($this->courseService->getCourseDetails($id));
    }

    /**
     * @OA\Post(
     * path="/api/courses",
     * summary="Créer un nouveau cours (Enseignant uniquement)",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"title","description","price","category_id"},
     * @OA\Property(property="title", type="string", example="Laravel Avancé"),
     * @OA\Property(property="description", type="string", example="Maîtrisez le Repository Pattern"),
     * @OA\Property(property="price", type="number", format="float", example=49.99),
     * @OA\Property(property="category_id", type="integer", example=1)
     * )
     * ),
     * @OA\Response(response=201, description="Cours créé"),
     * @OA\Response(response=403, description="Accès refusé"),
     * @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id'
        ]);

        return response()->json($this->courseService->createCourse($data), 201);
    }

    /**
     * @OA\Put(
     * path="/api/courses/{id}",
     * summary="Modifier un cours existant",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(
     * @OA\JsonContent(
     * @OA\Property(property="title", type="string"),
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="price", type="number")
     * )
     * ),
     * @OA\Response(response=200, description="Cours mis à jour"),
     * @OA\Response(response=403, description="Vous n'êtes pas l'auteur de ce cours")
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
        ]);

        try {
            $course = $this->courseService->updateCourse($id, $data);
            return response()->json($course);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/courses/{id}",
     * summary="Supprimer un cours",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="Cours supprimé"),
     * @OA\Response(response=403, description="Action non autorisée")
     * )
     */
    public function destroy($id)
    {
        try {
            $this->courseService->deleteCourse($id);
            return response()->json(['message' => 'Cours supprimé']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }
    /**
     * @OA\Get(
     * path="/api/recommendations",
     * summary="Obtenir des recommandations basées sur les intérêts",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="Liste des recommandations")
     * )
     */
    public function recommendations()
    {
        $courses = $this->courseService->getRecommendations();
        return response()->json($courses);
    }

    /**
     * @OA\Post(
     * path="/api/courses/{id}/favorite",
     * summary="Ajouter ou retirer un cours des favoris",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="Statut du favori mis à jour"),
     * @OA\Response(response=404, description="Cours introuvable")
     * )
     */
    public function toggleFavorite($id)
    {
        try {
            $result = $this->courseService->toggleFavorite($id);
            $status = count($result['attached']) > 0 ? 'ajouté aux' : 'retiré des';
            return response()->json(['message' => "Cours $status favoris."]);
        } catch (\Exception $e) {
            return response()->json(['error' => "Cours introuvable"], 404);
        }
    }

    /**
     * @OA\Get(
     * path="/api/favorites",
     * summary="Liste de mes cours favoris",
     * tags={"Cours"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(response=200, description="Liste des favoris récupérée")
     * )
     */
    public function favorites()
    {
        return response()->json($this->courseService->getMyFavorites());
    }
}