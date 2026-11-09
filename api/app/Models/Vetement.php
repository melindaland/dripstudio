<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Un vêtement de la garde-robe.
 *
 * Les scopes définis ici sont les briques de l'algorithme de tenues :
 * ils permettent d'écrire la sélection d'une pièce en une seule ligne
 * lisible, plutôt qu'en une cascade de conditions.
 */
class Vetement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vetements';

    protected $fillable = [
        'user_id',
        'categorie_id',
        'nom',
        'couleur_hex',
        'motif',
        'temp_min',
        'temp_max',
        'impermeable',
        'coupe_vent',
        'nb_ports',
        'derniere_fois_porte',
    ];

    protected function casts(): array
    {
        return [
            'temp_min' => 'integer',
            'temp_max' => 'integer',
            'impermeable' => 'boolean',
            'coupe_vent' => 'boolean',
            'nb_ports' => 'integer',
            'derniere_fois_porte' => 'date',
        ];
    }

    // ───────────────────────── Relations ─────────────────────────

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ImageVetement::class, 'vetement_id');
    }

    public function tenues(): BelongsToMany
    {
        return $this->belongsToMany(Tenue::class, 'vetements_tenues', 'vetement_id', 'tenue_id')
                    ->withPivot('ordre_affichage');
    }

    // ───────────────────── Scopes de l'algorithme ─────────────────────

    /**
     * Pièces dont la plage d'usage couvre la température donnée.
     */
    public function scopeAdapteA(Builder $query, float $temperature): Builder
    {
        return $query->where('temp_min', '<=', $temperature)
                     ->where('temp_max', '>=', $temperature);
    }

    /**
     * Restreint aux pièces imperméables. À appliquer sur la couche
     * extérieure quand la météo annonce de la pluie.
     */
    public function scopeImpermeable(Builder $query): Builder
    {
        return $query->where('impermeable', true);
    }

    /**
     * Cœur de la démarche éco-responsable : les pièces jamais portées
     * remontent en tête, puis les plus anciennement portées.
     */
    public function scopeOubliesDabord(Builder $query): Builder
    {
        return $query->orderByRaw('derniere_fois_porte IS NULL DESC')
                     ->orderBy('derniere_fois_porte', 'asc')
                     ->orderBy('nb_ports', 'asc');
    }

    /**
     * Pièces occupant un emplacement donné du corps.
     */
    public function scopePourEmplacement(Builder $query, string $emplacement): Builder
    {
        return $query->whereHas('categorie', function (Builder $q) use ($emplacement) {
            $q->where('emplacement', $emplacement)
              ->orWhere('emplacement_secondaire', $emplacement);
        });
    }
}
