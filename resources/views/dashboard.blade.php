<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAM - Centro de Comando</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0f172a;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }
    </style>
</head>

<body class="bg-slate-900 text-slate-200 h-screen overflow-hidden flex flex-col font-sans">

    <div class="p-6 flex-shrink-0 border-b border-slate-800 bg-slate-900 shadow-sm z-20 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-white flex items-center gap-3">
                📡 Unidad Automatizada de Monitoreo
            </h1>
            <p class="text-slate-400 text-xs mt-1">
                Panel de Auditoría Forense en Tiempo Real
            </p>
        </div>
        <div class="flex items-center space-x-2 bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
            </span>
            <span class="text-green-400 text-[10px] font-bold uppercase tracking-wider" id="sync-status">
                Live Sync
            </span>
        </div>
    </div>

    <div class="flex-1 p-6 overflow-hidden flex flex-col gap-4">

        <div class="bg-slate-800 p-4 rounded-xl border border-slate-700 flex-shrink-0">
            <form method="GET" action="{{ route('dashboard.index') }}" class="flex flex-wrap gap-4 items-end" id="filter-form">
                
                <div class="w-40">
                    <label class="block text-[10px] text-slate-400 uppercase tracking-wide mb-1 font-bold">Usuario ID</label>
                    <input type="text" name="employee" value="{{ request('employee') }}" placeholder="Ej. DEV-001" class="w-full bg-slate-900 border border-slate-700 text-white rounded px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <div class="w-36">
                    <label class="block text-[10px] text-slate-400 uppercase tracking-wide mb-1 font-bold">App / Ventana</label>
                    <input type="text" name="app_name" value="{{ request('app_name') }}" placeholder="Ej. WhatsApp" class="w-full bg-slate-900 border border-slate-700 text-white rounded px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                </div>

                <div class="w-36">
                    <label class="block text-[10px] text-slate-400 uppercase tracking-wide mb-1 font-bold">Tiempo</label>
                    <select name="period" id="period-select" class="w-full bg-slate-900 border border-slate-700 text-white rounded px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="">Todo el histórico</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Esta semana</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Este mes</option>
                        <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Personalizado...</option>
                    </select>
                </div>

                <div id="custom-dates" class="flex gap-4" style="display: {{ request('period') == 'custom' ? 'flex' : 'none' }};">
                    <div class="w-36">
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wide mb-1 font-bold">Día (o Desde)</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none" style="color-scheme: dark;">
                    </div>

                    <div class="w-36">
                        <label class="block text-[10px] text-slate-400 uppercase tracking-wide mb-1 font-bold">Hasta (Opcional)</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="w-full bg-slate-900 border border-slate-700 text-white rounded px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 outline-none" style="color-scheme: dark;">
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded text-xs font-bold transition">Filtrar</button>
                    <a href="{{ route('dashboard.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded text-xs font-bold transition">Limpiar</a>
                </div>

                <div class="ml-auto flex gap-2 border-l border-slate-700 pl-4">
                    <button type="submit" formaction="{{ route('dashboard.export.excel') }}" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded text-xs font-bold transition flex items-center gap-1">
                        📊 Excel
                    </button>
                    <button type="submit" formaction="{{ route('dashboard.export.pdf') }}" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded text-xs font-bold transition flex items-center gap-1">
                        📄 PDF
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden shadow-2xl flex flex-col flex-1 relative">
            <div class="overflow-y-auto flex-1">
                <table class="w-full text-left relative">
                    <thead class="bg-slate-950 text-slate-400 text-[10px] uppercase tracking-wider sticky top-0 z-10 shadow-md">
                        <tr>
                            <th class="p-4 w-32">Timestamp</th>
                            <th class="p-4 w-32">ID Usuario</th>
                            <th class="p-4 w-36">Tipo Acción</th>
                            <th class="p-4">Detalle Forense</th>
                        </tr>
                    </thead>
                    <tbody id="logs-table-body" class="divide-y divide-slate-700">
                        @forelse($logs as $log)
                        <tr class="hover:bg-slate-700/50 transition duration-150">

                            <td class="p-4 text-xs text-slate-400 font-mono">
                                <div class="text-slate-300">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div>{{ $log->created_at->format('H:i:s') }}</div>
                            </td>

                            <td class="p-4">
                                <span class="bg-slate-900 border border-slate-700 text-slate-300 px-2 py-1 rounded text-[10px] font-bold">
                                    {{ $log->employee_identifier }}
                                </span>
                            </td>

                            <td class="p-4">
                                @if($log->event_type === 'navigation')
                                <span class="text-blue-400 bg-blue-900/30 border border-blue-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">Navegación</span>
                                @elseif($log->event_type === 'keystroke')
                                <span class="text-indigo-400 bg-indigo-900/30 border border-indigo-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">Teclado</span>
                                @elseif($log->event_type === 'clipboard')
                                <span class="text-fuchsia-400 bg-fuchsia-900/30 border border-fuchsia-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">Portapapeles</span>
                                @elseif($log->event_type === 'form_submit')
                                <span class="text-amber-400 bg-amber-900/30 border border-amber-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">Formulario</span>
                                @elseif($log->event_type === 'window_focus')
                                <span class="text-cyan-400 bg-cyan-900/30 border border-cyan-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">App Activa</span>
                                @elseif($log->event_type === 'screenshot')
                                <span class="text-rose-400 bg-rose-900/30 border border-rose-800 px-2 py-1 rounded text-[10px] font-bold flex items-center w-max">Captura</span>
                                @endif
                            </td>

                            <td class="p-4">
                                <div class="text-sm font-semibold text-slate-200 mb-0.5">{{ $log->window_title ?? 'Sin título' }}</div>
                                <div class="text-[11px] text-blue-400 truncate max-w-xl mb-2 hover:underline cursor-pointer"><a href="{{ $log->url_or_path }}" target="_blank">{{ $log->url_or_path }}</a></div>

                                <div class="mt-1">
                                    @if($log->event_type === 'navigation')
                                    <p class="text-slate-500 text-[11px] italic">Acceso o recarga de la página.</p>
                                    @elseif($log->event_type === 'keystroke')
                                    <div class="bg-slate-900 p-2 rounded border border-slate-700">
                                        <span class="text-green-400 font-mono text-[11px] break-words">"{{ $log->payload['texto_capturado'] ?? '' }}"</span>
                                    </div>
                                    @elseif($log->event_type === 'clipboard')
                                    <div class="bg-slate-900 p-2 rounded border border-slate-700 border-l-2 border-l-fuchsia-500">
                                        <span class="text-fuchsia-400 font-mono text-[11px] break-words">{{ $log->payload['texto_capturado'] ?? '' }}</span>
                                    </div>
                                    @elseif($log->event_type === 'form_submit')
                                    <div class="bg-slate-900 p-2 rounded border border-slate-700 border-l-2 border-l-amber-500">
                                        @foreach($log->payload as $key => $value)
                                        <div class="text-[11px]"><span class="text-slate-400 font-bold uppercase">{{ $key }}:</span> <span class="text-slate-300">{{ is_array($value) ? 'Datos Complejos' : $value }}</span></div>
                                        @endforeach
                                    </div>
                                    @elseif($log->event_type === 'window_focus')
                                    <div class="bg-slate-900 p-2 rounded border border-slate-700 border-l-2 border-l-cyan-500">
                                        <span class="text-cyan-400 font-mono text-[11px]">El usuario cambió a esta aplicación.</span>
                                    </div>
                                    @elseif($log->event_type === 'screenshot')
                                    <div class="bg-slate-900 p-2 rounded border border-slate-700 border-l-2 border-l-rose-500 mt-2">
                                        <a href="{{ asset($log->payload['image_path']) }}" target="_blank">
                                            <img src="{{ asset($log->payload['image_path']) }}" alt="Screenshot" class="w-64 rounded shadow-md border border-slate-700 hover:opacity-80 transition cursor-pointer">
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500 text-sm">No se encontraron registros.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex-shrink-0" id="pagination-container">
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </div>

    <script>
        setInterval(async () => {
            try {
                // Obtenemos href completo para que la paginacion y el polling respeten los filtros activos
                const response = await fetch(window.location.href);
                const html = await response.text();

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTbody = doc.getElementById('logs-table-body');
                const newPagination = doc.getElementById('pagination-container');

                if (newTbody) document.getElementById('logs-table-body').innerHTML = newTbody.innerHTML;
                if (newPagination) document.getElementById('pagination-container').innerHTML = newPagination.innerHTML;

            } catch (error) {
                console.error('Error Sync UAM:', error);
                document.getElementById('sync-status').classList.replace('text-green-400', 'text-red-400');
                document.getElementById('sync-status').innerText = 'Falla Red';
            }
        }, 3000);
    </script>

    <script>
        // Logica para mostrar/ocultar campos de fecha personalizada
        document.getElementById('period-select').addEventListener('change', function() {
            const customDatesContainer = document.getElementById('custom-dates');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            if (this.value === 'custom') {
                customDatesContainer.style.display = 'flex';
            } else {
                customDatesContainer.style.display = 'none';
                // Limpiamos los campos para que no interfieran con los atajos Hoy/Semana/Mes
                startDateInput.value = '';
                endDateInput.value = '';
            }
        });
    </script>
</body>

</html>