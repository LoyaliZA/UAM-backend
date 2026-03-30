<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // <- NUEVO: Herramienta de archivos

class ActivityLogController extends Controller
{
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'employee_identifier' => 'required|string|max:100',
            // Agregamos 'screenshot' a los eventos permitidos
            'event_type'          => 'required|string|in:navigation,form_submit,clipboard,keystroke,window_focus,screenshot',
            'url_or_path'         => 'nullable|string',
            'window_title'        => 'nullable|string|max:255',
            'payload'             => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {
            $safeData = $validator->validated();
            $safeData['ip_address'] = $request->ip();

            // ==========================================
            // MAGIA PARA FOTOS: Interceptamos las capturas
            // ==========================================
            if ($safeData['event_type'] === 'screenshot' && isset($safeData['payload']['image_base64'])) {
                
                // 1. Limpiamos el texto base64 por si trae cabeceras extrañas
                $imageBase64 = $safeData['payload']['image_base64'];
                if (strpos($imageBase64, ',') !== false) {
                    @list($type, $imageBase64) = explode(',', $imageBase64);
                }
                
                // 2. Generamos un nombre único y guardamos físicamente en el disco
                $imageName = 'screenshots/' . $safeData['employee_identifier'] . '_' . time() . '.jpg';
                Storage::disk('public')->put($imageName, base64_decode($imageBase64));

                // 3. Borramos el texto gigante y guardamos SOLO la rutita
                $safeData['payload'] = ['image_path' => 'storage/' . $imageName];
            }

            // Guardamos en Base de Datos
            ActivityLog::create($safeData);
            return response()->json(['status' => 'success'], 201);

        } catch (\Exception $e) {
            Log::error('Fallo crítico UAM: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Fallo interno'], 500);
        }
    }
}