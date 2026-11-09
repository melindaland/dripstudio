<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trace d'une tenue effectivement portée un jour donné.
 */
class Port extends Model
{
    protected $table = 'ports';

    protected $fillable = [
        'tenue_id',
        'date_portee',
        'temperature_reelle',
    ];

    protected function casts(): array
    {
        return [
            'date_portee' => 'date',
            'temperature_reelle' => 'decimal:1',
        ];
    }

    public function tenue(): BelongsTo
    {
        return $this->belongsTo(Tenue::class, 'tenue_id');
    }
}
