<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une tenue est une COMPOSITION de vêtements.
 *
 * Elle ne dit pas si elle a été portée : c'est le rôle de la table `ports`.
 * Séparer les deux évite d'avoir une colonne date_portee à NULL sur la
 * quasi-totalité des lignes, et permet qu'une même tenue soit portée
 * plusieurs fois.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->cascadeOnDelete();

            $table->string('nom', 100)->nullable();

            // Contexte météo au moment de la GÉNÉRATION, conservé pour
            // pouvoir expliquer a posteriori pourquoi cette tenue a été proposée.
            $table->decimal('temperature_generation', 4, 1)->nullable();
            $table->string('description_meteo', 100)->nullable();

            $table->boolean('est_favori')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'est_favori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenues');
    }
};
