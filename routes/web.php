<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
 * Rutas del modulo de auditoria y reportes.
 */
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
Route::get('/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');