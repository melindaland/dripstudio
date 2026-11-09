<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Photos d'un vêtement.
 *
 * Un même vêtement a plusieurs versions de la même image : la photo brute
 * prise au téléphone, sa version détourée, et une vignette pour la grille
 * du dressing. D'où la colonne `type`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images_vetements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vetement_id')->constrained('vetements')->cascadeOnDelete();

            // Chemin relatif dans le disque Storage, jamais un chemin absolu :
            // permet de passer du stockage local à un stockage objet S3
            // sans toucher aux données.
            $table->string('chemin_fichier', 500);

            $table->enum('type', ['originale', 'detouree', 'vignette'])->default('originale');

            $table->timestamps();

            $table->index(['vetement_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images_vetements');
    }
};
