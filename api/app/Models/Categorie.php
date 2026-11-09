<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Catégorie de vêtement (T-shirt, Jean, Manteau…).
 *
 * Référentiel fermé, alimenté par CategorieSeeder. Porte les règles de
 * composition exploitées par l'algorithme de tenues.
 */
class Categorie extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'nom',
        'emplacement',
        'emplacement_secondaire',
        'couche',
        'obligatoire',
        'temp_min_defaut',
        'temp_max_defaut',
    ];

    protected function casts(): array
    {
        return [
            'couche' => 'integer',
            'obligatoire' => 'boolean',
            'temp_min_defaut' => 'integer',
            'temp_max_defaut' => 'integer',
        ];
    }

    public function vetements(): HasMany
    {
        return $this->hasMany(Vetement::class, 'categorie_id');
    }
}
