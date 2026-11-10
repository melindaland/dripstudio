<?php

use App\Http\Controllers\Api\CategorieController;
use Illuminate\Support\Facades\Route;

/**
 * Routes de l'API DripStudio
 * Toutes les routes de ce fichier sont préfixées par /api.
 */

/**
 * Route de diagnostic, pour vérifier depuis le téléphone qu'il joint bien l'ordi sur le réseau Wi-Fi.
 */
Route::get('/ping', fn() => [
    'statut'      => 'ok',
    'application' => 'DripStudio',
    'heure'       => now()->toIso8601String(),
]);

Route::get('/categories', [CategorieController::class, 'index']);
