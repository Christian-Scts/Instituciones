<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SOCIO - PUI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-slate-100 text-slate-900">
    <div class="min-h-screen flex">
        <aside class="w-72 bg-gradient-to-b from-blue-700 to-slate-900 text-white p-5 shadow-2xl">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">SOCIO - PUI</h1>
                <p class="text-blue-100 text-sm">Panel</p>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.empresas.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">
                    Empresas
                </a>
                <a href="{{ route('admin.reportes.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">
                    Reportes
                </a>
                <a href="{{ route('admin.logs.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">
                    Logs
                </a>
                <a href="{{ route('admin.pruebas.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">
                    Pruebas
                </a>
                <a href="{{ route('admin.seguridad.index') }}" class="block px-4 py-3 rounded-xl hover:bg-white/10 transition">   
                Seguridad
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 text-white shadow-soft">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-xl font-bold">PORTAL UNICO DE IDENTIDAD</h1>
                        <p class="text-sm opacity-80">Monitoreo de cumplimiento en tiempo real</p>
                    </div>
                    <div class="text-right text-sm">
                        <div>Ambiente: <strong>Productivo</strong></div>
                        <div>Versión: <strong>v1.0</strong></div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 rounded-2xl bg-green-100 text-green-800 border border-green-200 shadow-soft">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 rounded-2xl bg-red-100 text-red-800 border border-red-200 shadow-soft">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>