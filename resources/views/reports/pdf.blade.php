<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Forense UAM</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Actividad UAM</h2>
    <p>Fecha de generación: {{ now()->format('d/m/Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Evento / App</th>
                <th>Detalle Capturado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td style="width: 15%;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td style="width: 15%;">{{ $log->employee_identifier }}</td>
                <td style="width: 25%;">
                    <strong>{{ strtoupper($log->event_type) }}</strong><br>
                    {{ $log->window_title ?? 'Sin título' }}
                </td>
                <td style="width: 45%; word-wrap: break-word;">
                    {{ $log->payload_detail }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>