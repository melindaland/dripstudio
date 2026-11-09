<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Photo d'un vêtement, dans l'une de ses versions.
 */
class ImageVetement extends Model
{
    protected $table = 'images_vetements';

    protected $fillable = [
        'vetement_id',
        'chemin_fichier',
        'type',
    ];

    public function vetement(): BelongsTo
    {
        return $this->belongsTo(Vetement::class, 'vetement_id');
    }

    /**
     * URL publique calculée à la volée. On ne stocke jamais l'URL complète
     * en base : si le disque de stockage change (local vers S3), seules
     * les URL générées changent, pas les données.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->chemin_fichier);
    }
}
