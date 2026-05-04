<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | PUI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 to-slate-900 flex items-center justify-center px-4 py-10">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-red-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 bg-white/95 rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-blue-700 to-slate-950 text-white p-10">
            <div>
                <!--<div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/15 mb-6">
                    <span class="text-2xl font-bold">P</span>
                </div>-->

                <div class="inline-flex items-center justify-center w-15 h-15 rounded-2xl bg-white/18 mb-6">
                    <span class="text-2xl font-bold"></span>
                </div>

                <h1 class="text-4xl font-extrabold leading-tight">
                    SOCIO - PUI
                </h1>

                <p class="mt-4 text-blue-100 text-lg">
                    Plataforma de integración y monitoreo para la gestión de reportes, coincidencias y cumplimiento operativo.
                </p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-2xl bg-white/10 p-4">
                        <div class="text-xl font-bold">API</div>
                        <div class="text-xs text-blue-100">Integración</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <div class="text-xl font-bold">JWT</div>
                        <div class="text-xs text-blue-100">Seguridad</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4">
                        <div class="text-xl font-bold">LOG</div>
                        <div class="text-xs text-blue-100">Trazabilidad</div>
                    </div>
                </div>

                <p class="text-xs text-blue-100">
                    Acceso restringido a usuarios autorizados.
                </p>
            </div>
        </div>

        <div class="p-8 sm:p-12">
            <div class="mb-8">
                <div class="lg:hidden inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-700 text-white mb-5">
                    <span class="text-2xl font-bold">P</span>
                </div>

                <h2 class="text-3xl font-extrabold text-slate-900">
                    Iniciar sesión
                </h2>
                <p class="mt-2 text-slate-500">
                    Ingresa tus credenciales para acceder al panel administrativo.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition"
                        placeholder="admin@pui.test"
                    >
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Contraseña
                    </label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder-slate-400 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:outline-none transition"
                        placeholder="••••••••"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-blue-700 text-white py-3.5 font-bold hover:bg-blue-800 transition shadow-lg shadow-blue-700/30"
                >
                    Entrar al panel
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-200">
                <p class="text-xs text-slate-500 text-center">
                    Plataforma de Integración SOCIO · PUI
                </p>
            </div>
        </div>
    </div>

</body>
</html>