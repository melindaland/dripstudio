<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Traduit un Vetement en JSON pour l'app.
 */
class VetementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nom'         => $this->nom,
            'couleur_hex' => $this->couleur_hex,
            'motif'       => $this->motif,
            'temperatures' => [
                'min' => $this->temp_min,
                'max' => $this->temp_max,
            ],

            'impermeable' => $this->impermeable,
            'coupe_vent'  => $this->coupe_vent,

            // Afficher un badge "jamais porté" dans le dressing.
            'usage' => [
                'nb_ports'            => $this->nb_ports,
                'derniere_fois_porte' => $this->derniere_fois_porte?->toDateString(),
                'jamais_porte'        => $this->nb_ports === 0,
            ],

            // N'apparaît que si le contrôleur a chargé la relation.
            'categorie' => new CategorieResource($this->whenLoaded('categorie')),

            // L'image à afficher
            'photo_url' => $this->whenLoaded('images', function () {
                $image = $this->images->firstWhere('type', 'detouree')
                    ?? $this->images->firstWhere('type', 'originale')
                    ?? $this->images->first();

                return $image?->url;
            }),
        ];
    }
}
