<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Une composition de vêtements.
 *
 * Ne dit rien de son port effectif : c'est la relation `ports` qui le fait.
 */
class Tenue extends Model
{
    use SoftDeletes;

    protected $table = 'tenues';

    protected $fillable = [
        'user_id',
        'nom',
        'temperature_generation',
        'description_meteo',
        'est_favori',
    ];

    protected function casts(): array
    {
        return [
            'temperature_generation' => 'decimal:1',
            'est_favori' => 'boolean',
        ];
    }

    /**
     * Les pièces de la tenue, déjà triées dans leur ordre d'empilement.
     */
    public function vetements(): BelongsToMany
    {
        return $this->belongsToMany(Vetement::class, 'vetements_tenues', 'tenue_id', 'vetement_id')
                    ->withPivot('ordre_affichage')
                    ->orderByPivot('ordre_affichage');
    }

    public function ports(): HasMany
    {
        return $this->hasMany(Port::class, 'tenue_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFavorites(Builder $query): Builder
    {
        return $query->where('est_favori', true);
    }
}
