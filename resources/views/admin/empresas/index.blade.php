@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Empresas</h2>
        <a href="{{ route('admin.empresas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Nueva empresa
        </a>
    </div>

    <!--<div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">RFC</th>
                    <th class="p-3 text-left">Razón social</th>
                    <th class="p-3 text-left">Ambiente</th>
                    <th class="p-3 text-left">IP</th>
                    <th class="p-3 text-left">Webhook</th>
                    <th class="p-3 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empresas as $empresa)
                    <tr class="border-t">
                        <td class="p-3">{{ preg_replace('/[^A-Za-z0-9]/', '', (string) $empresa->rfc) }}</td>
                        <td class="p-3">{{ $empresa->razon_social }}</td>
                        <td class="p-3">{{ strtoupper($empresa->ambiente) }}</td>
                        <td class="p-3">{{ $empresa->ip_registrada }}</td>
                        <td class="p-3">{{ $empresa->url_base_api }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.empresas.edit', $empresa) }}" class="px-3 py-1 bg-green-600 text-white rounded-lg">Editar</a>

                            <a href="{{ route('admin.empresas.panel', $empresa) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                                Panel
                            </a>

                            <a href="{{ route('admin.empresas.clientes.index', $empresa) }}" class="px-3 py-1 bg-green-600 text-white rounded-lg">
                                Clientes
                            </a>

                            <a href="{{ route('admin.pruebas.index', ['empresa_id' => $empresa->id]) }}" class="px-3 py-1 bg-blue-600 text-white rounded-lg">
                                Pruebas
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>-->

 <div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50">
            <tr>
                <th class="p-4 text-left">RFC</th>
                <th class="p-4 text-left">Razón social</th>
                <th class="p-4 text-left text-center">Ambiente</th>
                <th class="p-4 text-left">IP</th>
                <th class="p-4 text-left">Webhook</th>
                <th class="p-4 text-center">Acciones</th> </tr>
        </thead>
        <tbody>
            @foreach($empresas as $empresa)
                <tr class="border-t hover:bg-slate-50/50 transition-colors">
                    <td class="p-4 font-mono">{{ preg_replace('/[^A-Za-z0-9]/', '', (string) $empresa->rfc) }}</td>
                    <td class="p-4 font-medium text-slate-700">{{ $empresa->razon_social }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2 py-1 text-xs font-bold rounded-md {{ strtolower($empresa->ambiente) == 'produccion' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ strtoupper($empresa->ambiente) }}
                        </span>
                    </td>
                    <td class="p-4 text-slate-600">{{ $empresa->ip_registrada }}</td>
                    <td class="p-4">
                        <div class="max-w-[150px] truncate text-blue-600" title="{{ $empresa->url_base_api }}">
                            {{ $empresa->url_base_api }}
                        </div>
                    </td>
                    <td class="p-4 whitespace-nowrap"> <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.empresas.edit', $empresa) }}" 
                               class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Editar Empresa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <a href="{{ route('admin.empresas.panel', $empresa) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-md text-[10px] font-bold uppercase tracking-wider transition-all border border-blue-100 hover:border-blue-600">
                                Panel
                            </a>

                            <a href="{{ route('admin.empresas.clientes.index', $empresa) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-md text-[10px] font-bold uppercase tracking-wider transition-all border border-emerald-100 hover:border-emerald-600">
                                Clientes
                            </a>

                            <a href="{{ route('admin.pruebas.index', ['empresa_id' => $empresa->id]) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-slate-800 text-white hover:bg-black rounded-md text-[10px] font-bold uppercase tracking-wider transition-all shadow-sm">
                                Pruebas
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    
    <!--<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">RFC</th>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Razón Social</th>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Ambiente</th>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">IP Registrada</th>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Webhook / API</th>
                <th class="p-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($empresas as $empresa)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-4 font-mono text-sm text-slate-700">
                        {{ preg_replace('/[^A-Za-z0-9]/', '', (string) $empresa->rfc) }}
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-900">{{ $empresa->razon_social }}</div>
                    </td>
                    <td class="p-4 text-center">
                        @php
                            $isProd = strtolower($empresa->ambiente) === 'produccion' || strtolower($empresa->ambiente) === 'prod';
                            $colorClass = $isProd ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700';
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $colorClass }}">
                            {{ strtoupper($empresa->ambiente) }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-600">
                        <span class="inline-flex items-center">
                            <span class="w-2 h-2 mr-2 rounded-full bg-slate-300"></span>
                            {{ $empresa->ip_registrada ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="max-w-xs truncate text-sm text-blue-600 font-light" title="{{ $empresa->url_base_api }}">
                            {{ $empresa->url_base_api }}
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.empresas.edit', $empresa) }}" 
                               class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all" title="Editar Empresa">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <a href="{{ route('admin.empresas.panel', $empresa) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white rounded-md text-xs font-bold transition-all">
                                PANEL
                            </a>

                            <a href="{{ route('admin.empresas.clientes.index', $empresa) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-md text-xs font-bold transition-all">
                                CLIENTES
                            </a>

                            <a href="{{ route('admin.pruebas.index', ['empresa_id' => $empresa->id]) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-slate-800 text-white hover:bg-black rounded-md text-xs font-bold transition-all">
                                PRUEBAS
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>-->

    <div class="mt-4">{{ $empresas->links() }}</div>
@endsection