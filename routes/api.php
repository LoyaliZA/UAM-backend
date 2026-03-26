<?php

use App\Http\Controllers\Api\ActivityLogController;
use Illuminate\Support\Facades\Route;

// Todo lo que esté dentro de este grupo requerirá un Token válido
Route::middleware('auth:sanctum')->group(function () {
    
    // Endpoint para recibir los logs del UAM
    Route::post('/logs', [ActivityLogController::class, 'store']);
    
});