<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UAM - Panel de Auditoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-white">📡 Unidad Automatizada de Monitoreo</h1>
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="p-4">Timestamp</th>
                        <th class="p-4">Evento</th>
                        <th class="p-4">Actividad / Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="p-4 font-mono text-xs text-slate-500">{{ $log->created_at->format('H:i:s') }}</td>
                        <td class="p-4">
                            @php
                                $colors = ['navigation'=>'blue', 'form_submit'=>'amber', 'keystroke'=>'indigo', 'clipboard'=>'fuchsia'];
                                $c = $colors[$log->event_type] ?? 'slate';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold border border-{{$c}}-700 bg-{{$c}}-900/30 text-{{$c}}-400 uppercase">
                                {{ $log->event_type }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="text-blue-400 text-sm mb-2 truncate">{{ $log->url_or_path }}</div>
                            <div class="bg-slate-950 p-3 rounded border border-slate-700 font-mono text-xs">
                                @if($log->event_type === 'keystroke')
                                    <span class="text-green-400">⌨️ {{ $log->payload['texto_capturado'] ?? '' }}</span>
                                @elseif($log->event_type === 'clipboard')
                                    <span class="text-fuchsia-400">📋 {{ $log->payload['texto_capturado'] ?? '' }}</span>
                                @elseif($log->event_type === 'form_submit')
                                    <span class="text-amber-400 font-bold">⚠️ FORMULARIO:</span>
                                    <pre class="text-slate-400 mt-1">{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-slate-500">{{ json_encode($log->payload) }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html><!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>UAM - Panel de Auditoría</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-white">📡 Unidad Automatizada de Monitoreo</h1>
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden shadow-2xl">
            <table class="w-full text-left">
                <thead class="bg-slate-950 text-slate-400 text-xs uppercase">
                    <tr>
                        <th class="p-4">Timestamp</th>
                        <th class="p-4">Evento</th>
                        <th class="p-4">Actividad / Payload</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @foreach($logs as $log)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="p-4 font-mono text-xs text-slate-500">{{ $log->created_at->format('H:i:s') }}</td>
                        <td class="p-4">
                            @php
                                $colors = ['navigation'=>'blue', 'form_submit'=>'amber', 'keystroke'=>'indigo', 'clipboard'=>'fuchsia'];
                                $c = $colors[$log->event_type] ?? 'slate';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold border border-{{$c}}-700 bg-{{$c}}-900/30 text-{{$c}}-400 uppercase">
                                {{ $log->event_type }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="text-blue-400 text-sm mb-2 truncate">{{ $log->url_or_path }}</div>
                            <div class="bg-slate-950 p-3 rounded border border-slate-700 font-mono text-xs">
                                @if($log->event_type === 'keystroke')
                                    <span class="text-green-400">⌨️ {{ $log->payload['texto_capturado'] ?? '' }}</span>
                                @elseif($log->event_type === 'clipboard')
                                    <span class="text-fuchsia-400">📋 {{ $log->payload['texto_capturado'] ?? '' }}</span>
                                @elseif($log->event_type === 'form_submit')
                                    <span class="text-amber-400 font-bold">⚠️ FORMULARIO:</span>
                                    <pre class="text-slate-400 mt-1">{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    <span class="text-slate-500">{{ json_encode($log->payload) }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>