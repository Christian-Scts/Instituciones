<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpresaClienteImportacion extends Model
{
    protected $table = 'empresa_cliente_importaciones';

    protected $fillable = [
        'empresa_id',
        'archivo_original',
        'archivo_path',
        'extension',
        'total_filas',
        'filas_ok',
        'filas_error',
        'estatus',
        'errores_json',
        'mensaje_error',
        'sobrescribir',
        'iniciado_en',
        'terminado_en',
    ];

    protected $casts = [
        'errores_json' => 'array',
        'sobrescribir' => 'boolean',
        'iniciado_en' => 'datetime',
        'terminado_en' => 'datetime',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}