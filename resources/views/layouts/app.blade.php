<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SOCIO - PUI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 text-slate-900">
    @php
        $adminUser = session('admin_user');
    @endphp

    <div class="min-h-screen flex">
        <aside class="w-72 bg-gradient-to-b from-blue-700 to-slate-900 text-white p-5 shadow-2xl">
            <div class="mb-8">
                <h1 class="text-2xl font-bold">SOCIO - PUI</h1>
                <p class="text-blue-100 text-sm">Panel</p>
            </div>

            @php
    $menuPorRol = session('menu_por_rol', []);
@endphp

        <nav class="space-y-5">
            @forelse($menuPorRol as $grupo)
                <div>
                    <div class="px-4 mb-2 text-xs uppercase tracking-wider text-blue-200/80 font-bold">
                        {{ $grupo['nombre'] }}
                    </div>

                    <div class="space-y-1">
                        @foreach($grupo['items'] as $item)
                            @php
                                $active = false;

                                foreach (($item['active_patterns'] ?? []) as $pattern) {
                                    if (request()->is($pattern)) {
                                        $active = true;
                                        break;
                                    }
                                }
                            @endphp

                            <a href="{{ $item['url'] }}"
                            class="flex items-center justify-between px-4 py-3 rounded-xl transition
                                    {{ $active
                                        ? 'bg-white text-blue-800 font-bold shadow'
                                        : 'text-white hover:bg-white/10'
                                    }}">
                                <span>{{ $item['titulo'] }}</span>

                                @if($active)
                                    <span class="w-2 h-2 rounded-full bg-blue-700"></span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="px-4 py-3 rounded-xl bg-white/10 text-sm text-blue-100">
                    Sin menú asignado
                </div>
            @endforelse
        </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="mb-6 p-4 rounded-2xl bg-gradient-to-r from-blue-700 to-blue-900 text-white shadow-soft">
                <div class="flex justify-between items-center gap-6">
                    <div>
                        <h1 class="text-xl font-bold">PORTAL ÚNICO DE IDENTIDAD</h1>
                        <p class="text-sm opacity-80">Monitoreo de cumplimiento en tiempo real</p>
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="text-right text-sm">
                            <div>Ambiente: <strong>Productivo</strong></div>
                            <div>Versión: <strong>v1.0</strong></div>
                        </div>

                        @if($adminUser)
                            <div class="text-right">
                                <div class="text-sm font-semibold">
                                    {{ $adminUser['name'] ?? 'Usuario' }}
                                </div>
                                <div class="text-xs opacity-70">
                                    {{ $adminUser['email'] ?? '' }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition shadow">
                                    🔒 Salir
                                </button>
                            </form>
                        @endif
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