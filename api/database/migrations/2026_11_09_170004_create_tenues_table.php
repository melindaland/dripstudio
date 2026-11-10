<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Une tenue est une composition de vêtements.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->cascadeOnDelete();

            $table->string('nom', 100)->nullable();

            // Contexte météo au moment de la génération de la tenue pour permettre à lutilisateur de se souvenir de la raison pour laquelle il a choisi cette tenue.
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
