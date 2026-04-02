<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/categories",
     * summary="Récupérer toutes les catégories",
     * tags={"Cours"},
     * @OA\Response(response=200, description="Liste des catégories")
     * )
     */
    public function index()
    {
        // On peut passer par un service, ou ici en direct pour faire simple
        return response()->json(Category::all(['id', 'name']));
    }
}