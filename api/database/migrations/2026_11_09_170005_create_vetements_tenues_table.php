<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table de liaison de l'association N-N entre tenues et vêtements.
 *
 * Elle porte l'attribut ordre_affichage, qui détermine l'empilement visuel
 * des pièces lors du rendu de la tenue (du plus proche de la peau au plus
 * extérieur). Sa clé primaire est composite : une pièce ne peut pas figurer
 * deux fois dans la même tenue.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vetements_tenues', function (Blueprint $table) {
            $table->foreignId('tenue_id')->constrained('tenues')->cascadeOnDelete();
            $table->foreignId('vetement_id')->constrained('vetements')->cascadeOnDelete();

            $table->unsignedTinyInteger('ordre_affichage')->default(1);

            $table->primary(['tenue_id', 'vetement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vetements_tenues');
    }
};
