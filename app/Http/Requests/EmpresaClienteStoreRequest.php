<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaClienteStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'curp' => strtoupper(trim((string) $this->input('curp'))),
            'sexo_asignado' => strtoupper(trim((string) $this->input('sexo_asignado'))),
            'correo' => trim((string) $this->input('correo')),
        ]);
    }

    public function rules(): array
    {
        return [
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/'],
            'nombre' => ['nullable', 'string', 'max:50'],
            'primer_apellido' => ['nullable', 'string', 'max:50'],
            'segundo_apellido' => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'lugar_nacimiento' => ['nullable', 'string', 'max:20'],
            'sexo_asignado' => ['nullable', 'regex:/^[MHX]{1}$/'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'correo' => ['nullable', 'email', 'max:50'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'calle' => ['nullable', 'string', 'max:50'],
            'numero' => ['nullable', 'string', 'max:20'],
            'colonia' => ['nullable', 'string', 'max:50'],
            'codigo_postal' => ['nullable', 'regex:/^\d{5}$/'],
            'municipio_o_alcaldia' => ['nullable', 'string', 'max:100'],
            'entidad_federativa' => ['nullable', 'string', 'max:40'],
            'tipo_evento' => ['nullable', 'string', 'max:500'],
            'fecha_evento' => ['nullable', 'date'],
            'descripcion_lugar_evento' => ['nullable', 'string', 'max:500'],
            'fecha_referencia' => ['nullable', 'date'],
        ];
    }
}