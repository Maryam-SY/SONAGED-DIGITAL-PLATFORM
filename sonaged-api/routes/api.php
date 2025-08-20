<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route de test publique
Route::get('/test', function () {
    return response()->json([
        'message' => 'API SONAGED fonctionne correctement !',
        'timestamp' => now(),
        'cors' => 'enabled'
    ]);
});

// Routes publiques pour l'authentification
Route::post('/auth/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);

// Route publique pour créer des signalements (sans authentification)
Route::post('/signalements/public', [App\Http\Controllers\SignalementController::class, 'storePublic']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Routes des signalements
    Route::apiResource('signalements', App\Http\Controllers\SignalementController::class);
    Route::get('/signalements/statistics', [App\Http\Controllers\SignalementController::class, 'statistics']);
    
    // Routes des partenaires
    Route::apiResource('partenaires', App\Http\Controllers\PartenaireController::class);
    Route::get('/partenaires/nearby', [App\Http\Controllers\PartenaireController::class, 'nearby']);
    
    // Routes des collectes
    Route::apiResource('collectes', App\Http\Controllers\CollecteController::class);
    Route::post('/collectes/{id}/confirm', [App\Http\Controllers\CollecteController::class, 'confirm']);
    Route::post('/collectes/{id}/complete', [App\Http\Controllers\CollecteController::class, 'complete']);
    
    // Routes des déchets
    Route::apiResource('dechets', App\Http\Controllers\DechetController::class);
    Route::get('/dechets/search', [App\Http\Controllers\DechetController::class, 'search']);
    Route::get('/dechets/categories', [App\Http\Controllers\DechetController::class, 'categories']);
    Route::get('/dechets/recyclables', [App\Http\Controllers\DechetController::class, 'recyclables']);
    
    // Route de déconnexion
    Route::post('/auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    
    // Route du profil utilisateur
    Route::get('/auth/profile', [App\Http\Controllers\AuthController::class, 'profile']);

    // Route de rafraîchissement du token
    Route::post('/auth/refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
}); 