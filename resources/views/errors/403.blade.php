@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-16">
        <div class="bg-white rounded-3xl shadow p-10 text-center border border-slate-200">
            <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-6">
                <span class="text-4xl">🔒</span>
            </div>

            <h1 class="text-3xl font-bold text-slate-900 mb-3">
                Acceso restringido
            </h1>

            <p class="text-slate-500 mb-6">
                Tu usuario no tiene permisos para acceder a esta sección.
            </p>

            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex px-5 py-3 rounded-xl bg-blue-700 text-white font-semibold hover:bg-blue-800">
                Volver al dashboard
            </a>
        </div>
    </div>
@endsection