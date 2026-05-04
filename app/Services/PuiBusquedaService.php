<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\EmpresaCliente;
use App\Models\PuiReporte;

class PuiBusquedaService
{
    public function prepararFase1(PuiReporte $reporte): array
    {
        $cliente = EmpresaCliente::query()
            ->where('empresa_id', $reporte->empresa_id)
            ->where('curp', $reporte->curp)
            ->latest('fecha_referencia')
            ->first();

        if (!$cliente) {
            return [];
        }

        return [[
            'curp' => $cliente->curp,
            'lugar_nacimiento' => $cliente->lugar_nacimiento ?? 'DESCONOCIDO',
            'id' => $reporte->id_busqueda,
            'institucion_id' => $reporte->empresa->rfc,
            'fase_busqueda' => '1',
            'nombre_completo' => [
                'nombre' => $cliente->nombre,
                'primer_apellido' => $cliente->primer_apellido,
                'segundo_apellido' => $cliente->segundo_apellido,
            ],
            'fecha_nacimiento' => optional($cliente->fecha_nacimiento)->format('Y-m-d'),
            'sexo_asignado' => $cliente->sexo_asignado,
            'telefono' => $cliente->telefono,
            'correo' => $cliente->correo,
            'domicilio' => [
                'direccion' => $cliente->direccion,
                'calle' => $cliente->calle,
                'numero' => $cliente->numero,
                'colonia' => $cliente->colonia,
                'codigo_postal' => $cliente->codigo_postal,
                'municipio_o_alcaldia' => $cliente->municipio_o_alcaldia,
                'entidad_federativa' => $cliente->entidad_federativa,
            ],
        ]];
    }

    public function ejecutarHistorica(PuiReporte $reporte): array
    {
       $desde = $reporte->fecha_desaparicion ?? now()->subYears(12)->toDateString();

        return EmpresaCliente::query()
            ->where('empresa_id', $reporte->empresa_id)
            ->where('curp', $reporte->curp)
            ->whereDate('fecha_referencia', '>=', $desde)
            ->orderByDesc('fecha_referencia')
            ->get()
            ->map(function (EmpresaCliente $cliente) use ($reporte) {
                return [
                    'curp' => $cliente->curp,
                    'lugar_nacimiento' => $cliente->lugar_nacimiento ?? 'DESCONOCIDO',
                    'id' => $reporte->id_busqueda,
                    'institucion_id' => $reporte->empresa->rfc,
                    'fase_busqueda' => '2',
                    'nombre_completo' => [
                        'nombre' => $cliente->nombre,
                        'primer_apellido' => $cliente->primer_apellido,
                        'segundo_apellido' => $cliente->segundo_apellido,
                    ],
                    'fecha_nacimiento' => optional($cliente->fecha_nacimiento)->format('Y-m-d'),
                    'sexo_asignado' => $cliente->sexo_asignado,
                    'telefono' => $cliente->telefono,
                    'correo' => $cliente->correo,
                    'domicilio' => [
                        'direccion' => $cliente->direccion,
                        'calle' => $cliente->calle,
                        'numero' => $cliente->numero,
                        'colonia' => $cliente->colonia,
                        'codigo_postal' => $cliente->codigo_postal,
                        'municipio_o_alcaldia' => $cliente->municipio_o_alcaldia,
                        'entidad_federativa' => $cliente->entidad_federativa,
                    ],
                    'tipo_evento' => $cliente->tipo_evento,
                    'fecha_evento' => optional($cliente->fecha_evento)->format('Y-m-d'),
                    'descripcion_lugar_evento' => $cliente->descripcion_lugar_evento,
                    'direccion_evento' => $cliente->direccion_evento_json,
                ];
            })
            ->toArray();
    }

    public function ejecutarContinua(PuiReporte $reporte): array
{
    $desde = $reporte->fecha_desaparicion ?? now()->subYears(12)->toDateString();

    return EmpresaCliente::query()
        ->where('empresa_id', $reporte->empresa_id)
        ->where('curp', $reporte->curp)
        ->whereDate('fecha_referencia', '>=', $desde)
        ->orderByDesc('fecha_referencia')
        ->get()
        ->map(function (EmpresaCliente $cliente) use ($reporte) {
            return [
                'curp' => $cliente->curp,
                'lugar_nacimiento' => $cliente->lugar_nacimiento ?? 'DESCONOCIDO',
                'id' => $reporte->id_busqueda,
                'institucion_id' => $reporte->empresa->rfc,
                'fase_busqueda' => '3',
                'nombre_completo' => [
                    'nombre' => $cliente->nombre,
                    'primer_apellido' => $cliente->primer_apellido,
                    'segundo_apellido' => $cliente->segundo_apellido,
                ],
                'fecha_nacimiento' => optional($cliente->fecha_nacimiento)->format('Y-m-d'),
                'sexo_asignado' => $cliente->sexo_asignado,
                'telefono' => $cliente->telefono,
                'correo' => $cliente->correo,
                'domicilio' => [
                    'direccion' => $cliente->direccion,
                    'calle' => $cliente->calle,
                    'numero' => $cliente->numero,
                    'colonia' => $cliente->colonia,
                    'codigo_postal' => $cliente->codigo_postal,
                    'municipio_o_alcaldia' => $cliente->municipio_o_alcaldia,
                    'entidad_federativa' => $cliente->entidad_federativa,
                ],
                'tipo_evento' => $cliente->tipo_evento,
                'fecha_evento' => optional($cliente->fecha_evento)->format('Y-m-d'),
                'descripcion_lugar_evento' => $cliente->descripcion_lugar_evento,
                'direccion_evento' => $cliente->direccion_evento_json,
            ];
        })
        ->toArray();
}

    public function ejecutarContinua_otro(PuiReporte $reporte): array
    {
     return $this->ejecutarHistorica($reporte);
    }
}