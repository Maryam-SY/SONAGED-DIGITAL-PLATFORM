<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'SONAGED Digital Platform API',
        'version' => '1.0.0',
        'status' => 'running'
    ]);
});

// Route de test pour vérifier que l'API fonctionne
Route::get('/api/test', function () {
    return response()->json([
        'message' => 'API SONAGED fonctionne correctement !',
        'timestamp' => now(),
        'cors' => 'enabled'
    ]);
});
