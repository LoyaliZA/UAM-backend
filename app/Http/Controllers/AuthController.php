<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*
     * Muestra la vista del formulario de inicio de sesion.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /*
     * Procesa la autenticacion del administrador y audita el acceso.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // ==========================================
            // AUDITORIA: Registramos quien entro al UAM
            // ==========================================
            \App\Models\ActivityLog::create([
                'employee_identifier' => '🛡️ ADMIN: ' . Auth::user()->name,
                'event_type'          => 'admin_login',
                'window_title'        => 'Acceso al Centro de Comando UAM',
                'url_or_path'         => $request->fullUrl(),
                'ip_address'          => $request->ip(),
                'payload'             => [
                    'email'      => Auth::user()->email,
                    'user_agent' => $request->userAgent()
                ],
            ]);

            // Redirige al panel principal
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ])->onlyInput('email');
    }

    /*
     * Cierra la sesion del administrador y destruye la cookie.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}