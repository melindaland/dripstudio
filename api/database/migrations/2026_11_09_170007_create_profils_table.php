<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Configuration locale de l'utilisateur (pour l'instant une personne).
 *
 * Une seule ligne en mono-utilisateur.
 * Lalatitude / longitude mémorisent la dernière position connue : si la géolocalisation échoue au lancement
 * (GPS coupé, permission refusée), l'app peut tout de même proposer une
 * tenue au lieu d'afficher une erreur.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->cascadeOnDelete();

            $table->string('pseudo', 50)->nullable();
            $table->string('taille_haut', 10)->nullable();
            $table->string('taille_bas', 10)->nullable();
            $table->unsignedTinyInteger('pointure')->nullable();

            $table->string('ville_defaut', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profils');
    }
};
