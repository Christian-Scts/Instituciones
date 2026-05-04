<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpresaCliente extends Model
{
    protected $table = 'empresa_clientes';

    protected $fillable = [
        'empresa_id',
        'curp',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'lugar_nacimiento',
        'sexo_asignado',
        'telefono',
        'correo',
        'direccion',
        'calle',
        'numero',
        'colonia',
        'codigo_postal',
        'municipio_o_alcaldia',
        'entidad_federativa',
        'tipo_evento',
        'fecha_evento',
        'descripcion_lugar_evento',
        'direccion_evento_json',
        'domicilio_json',
        'fotos_json',
        'formato_fotos',
        'huellas_json',
        'formato_huellas',
        'fecha_referencia',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_evento' => 'date',
        'fecha_referencia' => 'date',
        'direccion_evento_json' => 'array',
        'domicilio_json' => 'array',
        'fotos_json' => 'array',
        'huellas_json' => 'array',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}