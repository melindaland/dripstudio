<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Un vêtement physique de la garde-robe.
 * nb_ports et derniere_fois_porte : remettre en avant les pièces oubliées.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vetements', function (Blueprint $table) {
            $table->id();

            // Présent dès maintenant même si l'app est mono-utilisateur
            $table->foreignId('user_id')->default(1)->constrained()->cascadeOnDelete();

            $table->foreignId('categorie_id')->constrained('categories')->restrictOnDelete();

            $table->string('nom', 100);

            // couleur en hexadécimal
            $table->char('couleur_hex', 7)->nullable();

            $table->string('motif', 50)->nullable();

            // Plage de température en degrés Celsius où la pièce est adaptée.
            $table->smallInteger('temp_min')->default(-10);
            $table->smallInteger('temp_max')->default(40);

            $table->boolean('impermeable')->default(false);
            $table->boolean('coupe_vent')->default(false);

            $table->unsignedInteger('nb_ports')->default(0);
            $table->date('derniere_fois_porte')->nullable();

            $table->timestamps();

            // Supprimer un vêtement ne doit pas effacer l'historique des tenues qui le contenaient.
            $table->softDeletes();

            $table->index(['user_id', 'categorie_id']);
            $table->index(['temp_min', 'temp_max']);
            $table->index('derniere_fois_porte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vetements');
    }
};
