<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Référentiel des catégories de vêtements.
 *
 * Cette table ne se contente pas de nommer les catégories : elle porte
 * l'intelligence de composition des tenues. C'est elle qui dit à l'algorithme
 * qu'un pull et un t-shirt occupent tous deux le haut du corps mais à des
 * couches différentes, et qu'une tenue sans chaussures n'est pas valide.
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

            // Certaines pièces couvrent deux emplacements : une robe occupe
            // le haut ET le bas. L'algorithme saute alors la case secondaire.
            $table->enum('emplacement_secondaire', ['haut', 'bas', 'pieds', 'exterieur', 'accessoire'])
                  ->nullable();

            // Ordre de superposition : 1 = au contact de la peau, 3 = extérieur.
            // Sert à calculer ordre_affichage lors du rendu de la tenue.
            $table->unsignedTinyInteger('couche')->default(1);

            // Une tenue valide exige au moins une pièce de cet emplacement.
            $table->boolean('obligatoire')->default(false);

            // Plage de température typique de la catégorie, en degrés Celsius.
            // Sert à préremplir le formulaire de capture : même sans
            // reconnaissance d'image, une doudoune arrive avec -15/8 plutôt
            // qu'avec des champs vides.
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
