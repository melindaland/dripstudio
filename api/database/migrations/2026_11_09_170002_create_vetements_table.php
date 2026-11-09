<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Un vêtement physique de la garde-robe.
 *
 * Les colonnes temp_min / temp_max remplacent le champ "meteo" du modèle
 * initial : l'algorithme a besoin de comparer des nombres à la température
 * du jour, ce qu'une chaîne de caractères ne permettait pas.
 *
 * nb_ports et derniere_fois_porte sont ce qui rend possible l'objectif
 * éco-responsable du projet : remettre en avant les pièces oubliées.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vetements', function (Blueprint $table) {
            $table->id();

            // Présent dès maintenant alors que l'app est mono-utilisateur :
            // ajouter cette colonne plus tard imposerait de migrer toutes les
            // tables et de reprendre chaque requête.
            $table->foreignId('user_id')->default(1)->constrained()->cascadeOnDelete();

            $table->foreignId('categorie_id')->constrained('categories')->restrictOnDelete();

            $table->string('nom', 100);

            // Hexadécimal et non un nom de couleur : permet la conversion en
            // HSL et le calcul des harmonies entre pièces.
            $table->char('couleur_hex', 7)->nullable();

            $table->string('motif', 50)->nullable();

            // Plage de température en degrés Celsius où la pièce est adaptée.
            $table->smallInteger('temp_min')->default(-10);
            $table->smallInteger('temp_max')->default(40);

            $table->boolean('impermeable')->default(false);
            $table->boolean('coupe_vent')->default(false);

            // Compteur dénormalisé : évite une jointure coûteuse sur `ports`
            // à chaque génération de tenue.
            $table->unsignedInteger('nb_ports')->default(0);
            $table->date('derniere_fois_porte')->nullable();

            $table->timestamps();

            // Suppression logique : supprimer un vêtement ne doit pas
            // effacer l'historique des tenues qui le contenaient.
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
