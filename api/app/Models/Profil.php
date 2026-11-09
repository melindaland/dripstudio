<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Configuration locale de l'utilisatrice : mensurations et localisation.
 */
class Profil extends Model
{
    protected $table = 'profils';

    protected $fillable = [
        'user_id',
        'pseudo',
        'taille_haut',
        'taille_bas',
        'pointure',
        'ville_defaut',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'pointure' => 'integer',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
