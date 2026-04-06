<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Rap2hpoutre\FastExcel\FastExcel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportService
{
    /*
     * Genera y descarga un archivo Excel usando FastExcel para bajo consumo de memoria.
     */
    public function generateExcel(Collection $logs)
    {
        $fileName = 'auditoria_uam_' . now()->format('Ymd_His') . '.xlsx';

        return (new FastExcel($logs))->download($fileName, function ($log) {
            return [
                'Fecha y Hora'    => $log->created_at->format('Y-m-d H:i:s'),
                'ID Usuario'      => $log->employee_identifier,
                'Tipo Evento'     => $log->event_type,
                'Aplicacion'      => $log->window_title,
                'Detalle Forense' => $log->payload_detail, // Invocamos el nuevo Accessor
                'URL o Ruta'      => $log->url_or_path,
                'IP Origen'       => $log->ip_address,
            ];
        });
    }

    /*
     * Genera y descarga un archivo PDF.
     */
    public function generatePdf(Collection $logs)
    {
        $fileName = 'auditoria_uam_' . now()->format('Ymd_His') . '.pdf';
        
        // DomPDF funciona mejor con vistas HTML simples
        $pdf = Pdf::loadView('reports.pdf', compact('logs'));
        
        return $pdf->download($fileName);
    }
}