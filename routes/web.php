<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Ruta principal que carga el Dashboard
Route::get('/', [DashboardController::class, 'index']);