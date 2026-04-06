<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAM - Acceso Restringido</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 h-screen flex items-center justify-center font-sans">
    <div class="bg-slate-800 p-8 rounded-xl border border-slate-700 shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-white">📡 UAM</h1>
            <p class="text-slate-400 text-xs mt-1">Acceso a Panel de Auditoría Forense</p>
        </div>

        <form method="POST" action="{{ route('login.post', [], false) }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs text-slate-400 uppercase tracking-wide mb-2 font-bold">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-900 border border-slate-700 text-white rounded px-4 py-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs text-slate-400 uppercase tracking-wide mb-2 font-bold">Contraseña</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 text-white rounded px-4 py-3 text-sm focus:ring-1 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white px-4 py-3 rounded text-sm font-bold transition">
                Ingresar al Sistema
            </button>
        </form>
    </div>
</body>
</html>