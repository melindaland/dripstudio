<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVetementRequest;
use App\Http\Resources\VetementResource;
use App\Models\Vetement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Gestion des vêtements du dressing.
 */
class VetementController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $vetements = Vetement::query()
            ->with(['categorie', 'images'])

            ->when(
                $request->filled('categorie_id'),
                fn($query) => $query->where('categorie_id', $request->integer('categorie_id'))
            )

            ->when(
                $request->filled('emplacement'),
                fn($query) => $query->pourEmplacement($request->string('emplacement'))
            )

            // Les derniers ajoutés en premier.
            ->latest()
            ->get();

        return VetementResource::collection($vetements);
    }

    /**
     * POST /api/vetements
     *
     * Reçoit les informations du vêtement et sa photo
     */
    public function store(StoreVetementRequest $request): JsonResponse
    {
        // Les données sont déjà validées par StoreVetementRequest.
        $donnees = $request->validated();

        // La photo est enregistrée avant la transaction
        $chemin = $request->file('photo')->store('vetements', 'public');

        try {
            $vetement = DB::transaction(function () use ($donnees, $chemin) {

                $vetement = Vetement::create([
                    // l'application est mono-utilisateur
                    'user_id'      => 1,
                    'categorie_id' => $donnees['categorie_id'],
                    'nom'          => $donnees['nom'],
                    'couleur_hex'  => $donnees['couleur_hex'] ?? null,
                    'motif'        => $donnees['motif'] ?? null,
                    'temp_min'     => $donnees['temp_min'],
                    'temp_max'     => $donnees['temp_max'],
                    'impermeable'  => $donnees['impermeable'] ?? false,
                    'coupe_vent'   => $donnees['coupe_vent'] ?? false,
                ]);

                // Grâce à la relation, Laravel remplit vetement_id tout seul.
                $vetement->images()->create([
                    'chemin_fichier' => $chemin,
                    'type'           => 'originale',
                ]);

                return $vetement;
            });
        } catch (\Throwable $e) {
            // La base a échoué, la transaction a tout annulé.
            Storage::disk('public')->delete($chemin);
            throw $e;
        }

        // Création réussie
        return (new VetementResource($vetement->load(['categorie', 'images'])))
            ->response()
            ->setStatusCode(201);
    }
}
