<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Cambiamos take(50)->get() por paginate(15) para mostrar 15 registros por página
        $logs = ActivityLog::latest()->paginate(15);
        
        return view('dashboard', compact('logs'));
    }
}