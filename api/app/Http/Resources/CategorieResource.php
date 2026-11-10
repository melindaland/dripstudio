<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Traduit une Categorie en JSON
 */
class CategorieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'nom'                    => $this->nom,
            'emplacement'            => $this->emplacement,
            'emplacement_secondaire' => $this->emplacement_secondaire,
            'couche'                 => $this->couche,
            'obligatoire'            => $this->obligatoire,

            // L'application reçoit un objet avec deux champs : "min" et "max".
            'temperatures_defaut'    => [
                'min' => $this->temp_min_defaut,
                'max' => $this->temp_max_defaut,
            ],
        ];
    }
}
