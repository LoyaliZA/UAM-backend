<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_identifier',
        'event_type',
        'url_or_path',
        'window_title',
        'payload',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /*
     * Scope para filtrar por identificador de empleado.
     */
    public function scopeByEmployee(Builder $query, ?string $employeeIdentifier): Builder
    {
        if ($employeeIdentifier) {
            return $query->where('employee_identifier', $employeeIdentifier);
        }
        return $query;
    }

    /*
     * Filtra por un rango de fechas. Si solo se provee startDate, busca solo en ese dia.
     */
    public function scopeByDateRange(Builder $query, ?string $startDate, ?string $endDate): Builder
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($startDate && !$endDate) {
            return $query->whereDate('created_at', $startDate);
        }

        return $query;
    }
    /*
     * Scope para buscar aplicaciones específicas en el título de la ventana.
     */
    public function scopeByApplication(Builder $query, ?string $appName): Builder
    {
        if ($appName) {
            return $query->where('window_title', 'LIKE', '%' . $appName . '%');
        }
        return $query;
    }

    /*
     * Filtra dinamicamente por periodos de tiempo predefinidos.
     */
    public function scopeByPeriod(Builder $query, ?string $period): Builder
    {
        if ($period === 'today') {
            return $query->whereDate('created_at', now()->toDateString());
        }

        if ($period === 'week') {
            return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        }

        if ($period === 'month') {
            return $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        return $query;
    }

    /*
     * Atributo virtual para extraer y formatear datos del payload segun el evento.
     */
    public function getPayloadDetailAttribute()
    {
        $payload = $this->payload;
        
        if (!is_array($payload)) {
            return 'Sin detalles adicionales';
        }

        switch ($this->event_type) {
            case 'keystroke':
            case 'clipboard':
                return $payload['texto_capturado'] ?? 'Sin texto';
                
            case 'form_submit':
                $details = [];
                foreach ($payload as $key => $value) {
                    $valStr = is_array($value) ? 'Datos Complejos' : $value;
                    $details[] = "{$key}: {$valStr}";
                }
                return implode(' | ', $details);
                
            case 'screenshot':
                return 'Captura alojada en: ' . ($payload['image_path'] ?? 'Desconocido');
                
            default:
                return 'N/A';
        }
    }

    /*
     * Scope para filtrar por el tipo de evento (acción).
     */
    public function scopeByEventType(Builder $query, ?string $eventType): Builder
    {
        if ($eventType) {
            return $query->where('event_type', $eventType);
        }
        return $query;
    }
}
