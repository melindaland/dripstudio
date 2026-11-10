<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Expose le référentiel des catégories de vêtements
 */
class CategorieController extends Controller
{
    /**
     * exemple : GET /api/categories
     * exemple : GET /api/categories?emplacement=pieds
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $categories = Categorie::query()
            ->when(
                $request->filled('emplacement'),
                fn($query) => $query->where('emplacement', $request->string('emplacement'))
            )
            // Tri par zone du corps, puis de la couche (la plus proche de la peau vers l'extérieur).
            ->orderBy('emplacement')
            ->orderBy('couche')
            ->orderBy('nom')
            ->get();

        return CategorieResource::collection($categories);
    }
}
