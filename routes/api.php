<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\PlataformaController;      
use App\Http\Controllers\GeneroController;  

// 1. Rutas Públicas (Cualquiera entra)
Route::post('/login', [AuthController::class, 'login']);
 
// Catálogo visible para todos (Solo Index y Show)
Route::get('/juegos', [JuegoController::class, 'index']);
Route::get('/juegos/{id}', [JuegoController::class, 'show']);
Route::get('/plataformas', [PlataformaController::class, 'index']);
 
// 2. Rutas Protegidas (Requieren Token Y ser Admin)
Route::middleware(['auth:sanctum', 'is_admin'])->group(function () {
// Logout
    Route::post('/logout', [AuthController::class, 'logout']);
 
    // Gestión completa de Juegos (excepto index/show que ya definimos arriba)
    Route::post('/juegos', [JuegoController::class, 'store']);
    Route::put('/juegos/{id}', [JuegoController::class, 'update']);
    Route::delete('/juegos/{id}', [JuegoController::class, 'destroy']);
 
    // Gestión de Plataformas y Géneros (Todo el CRUD)
    Route::apiResource('plataformas', PlataformaController::class)->except(['index']); // Index es público
    Route::apiResource('generos', GeneroController::class);
});
