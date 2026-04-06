<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Services\ReportService;

class DashboardController extends Controller
{
    /*
     * Muestra la vista principal con paginacion y parametros en URL.
     */
    public function index(Request $request)
    {
        $logs = $this->getFilteredQuery($request)
            ->paginate(15)
            ->withQueryString();
            
        return view('dashboard', compact('logs'));
    }

    /*
     * Extrae los datos y utiliza el servicio para generar Excel.
     */
    public function exportExcel(Request $request, ReportService $reportService)
    {
        $logs = $this->getFilteredQuery($request)->get();
        return $reportService->generateExcel($logs);
    }

    /*
     * Extrae los datos y utiliza el servicio para generar PDF.
     */
    public function exportPdf(Request $request, ReportService $reportService)
    {
        $logs = $this->getFilteredQuery($request)->get();
        return $reportService->generatePdf($logs);
    }

    /*
     * Centraliza la logica de busqueda para evitar codigo duplicado.
     * Retorna el constructor de consultas (Builder).
     */
    private function getFilteredQuery(Request $request)
    {
        return ActivityLog::query()
            ->byEmployee($request->input('employee'))
            ->byPeriod($request->input('period')) // Nuevo filtro agregado
            ->byDateRange($request->input('start_date'), $request->input('end_date'))
            ->byApplication($request->input('app_name'))
            ->latest();
    }
}