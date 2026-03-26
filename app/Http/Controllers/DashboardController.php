<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Traemos los últimos 50 registros, ordenados del más reciente al más antiguo
        $logs = ActivityLog::latest()->take(50)->get();
        
        // Enviamos la variable $logs a una vista llamada 'dashboard'
        return view('dashboard', compact('logs'));
    }
}