<?php

namespace App\Services;

use App\Jobs\ProcessBusquedaFase3Job;
use App\Models\PuiReporte;

class PuiResyncService
{
    public function resincronizarActivos(): int
    {
        $reportes = PuiReporte::whereIn('estatus', [
                'activo',
                'fase_1_completada',
                'fase_2_completada',
                'monitoreo_continuo',
            ])
            ->whereNull('baja_en')
            ->where('es_prueba', false)
            ->get();

        $contador = 0;

        foreach ($reportes as $reporte) {
            ProcessBusquedaFase3Job::dispatch($reporte->id);
            $contador++;
        }

        return $contador;
    }

    public function detectarAtrasados(int $horas = 24)
    {
        return PuiReporte::where('estatus', 'monitoreo_continuo')
            ->where('es_prueba', false)
            ->where(function ($q) use ($horas) {
                $q->whereNull('ultima_busqueda_continua_en')
                  ->orWhere('ultima_busqueda_continua_en', '<', now()->subHours($horas));
            })
            ->get();
    }
}