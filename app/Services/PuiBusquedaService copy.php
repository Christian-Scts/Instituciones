<?php

namespace App\Services;

use App\Models\PuiReporte;

class PuiBusquedaService
{
    public function prepararFase1(PuiReporte $reporte): array
    {
        $payload = [
            'id' => $reporte->id_busqueda,
            'curp' => $reporte->curp,
            'fase_busqueda' => '1',
            'nombre_completo' => [
                'nombre' => 'DEMO',
                'primer_apellido' => 'PRUEBA',
                'segundo_apellido' => 'PUI',
            ],
        ];

        return [
            'coincidencia' => true,
            'payload' => $payload,
        ];
    }

    public function ejecutarHistorica(PuiReporte $reporte): array
    {
        return [
            [
                'id' => $reporte->id_busqueda,
                'curp' => $reporte->curp,
                'fase_busqueda' => '2',
                'evento' => [
                    'tipo_evento' => 'MOVIMIENTO_ADMINISTRATIVO',
                    'fecha_evento' => now()->toDateString(),
                    'descripcion_lugar_evento' => 'Registro hist贸rico detectado',
                    'direccion_evento' => 'SIN_DATO',
                ],
            ],
        ];
    }

    public function ejecutarContinua(PuiReporte $reporte): array
    {
        return [];
    }
}