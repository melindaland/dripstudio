<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Historique réel des tenues portées.
 *
 * Alimente nb_ports et derniere_fois_porte sur vetements.
 * Cette table permet de savoir quelle pièces sont oubliées.
 *
 * Aussi temperature_reelle permet de savoir si une tenue a été portée par 5 °C alors qu'elle était calibrée pour 15 °C.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenue_id')->constrained('tenues')->cascadeOnDelete();

            $table->date('date_portee');
            $table->decimal('temperature_reelle', 4, 1)->nullable();

            $table->timestamps();

            $table->index('date_portee');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
