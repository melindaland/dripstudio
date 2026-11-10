<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Référentiel des catégories de vêtements.
 * Cette table est utilisée par l'algorithme de composition des tenues pour savoir quelles vêtements vont ensemble.
 * L'algorithme permet de générer des tenues completes à partir d'une sélection de vêtements, en respectant les règles de superposition et destyle.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 50)->unique();

            // Emplacement sur le corps. Détermine les "cases" à remplir.
            $table->enum('emplacement', ['haut', 'bas', 'pieds', 'exterieur', 'accessoire']);

            // Certaines pièces couvrent deux emplacements
            // Par exemple une robe occupe le haut ET le bas. L'algorithme saute alors la case secondaire.
            $table->enum('emplacement_secondaire', ['haut', 'bas', 'pieds', 'exterieur', 'accessoire'])
                  ->nullable();

            // Sert à calculer l'ordre_affichage lors du rendu de la tenue.
            // Ordre de superposition : 1 = au contact de la peau, 3 = extérieur.
            $table->unsignedTinyInteger('couche')->default(1);

            // Une tenue valide exige au moins une pièce de cet emplacement.
            $table->boolean('obligatoire')->default(false);

            // Plage de température de la catégorie, en degrés celsius.
            // Sert à préremplir le formulaire de capture de la tenue et à filtrer les tenues générées par l'algorithme.
            $table->smallInteger('temp_min_defaut')->default(-10);
            $table->smallInteger('temp_max_defaut')->default(40);

            $table->timestamps();

            $table->index('emplacement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
