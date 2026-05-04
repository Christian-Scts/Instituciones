<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuiCoincidencia extends Model
{
    protected $table = 'pui_coincidencias';

    protected $fillable = [
        'pui_reporte_id',
        'fase_busqueda',
        'curp',
        'nombre_completo',
        'domicilio',
        'evento',
        'payload_enviado',
        'respuesta_pui',
        'http_code',
        'notificado_en',
    ];

    protected $casts = [
        'nombre_completo' => 'array',
        'domicilio' => 'array',
        'evento' => 'array',
        'payload_enviado' => 'array',
        'respuesta_pui' => 'array',
        'notificado_en' => 'datetime',
    ];

    public function reporte(): BelongsTo
    {
        return $this->belongsTo(PuiReporte::class, 'pui_reporte_id');
    }

    public function scopeFase($query, string $fase)
    {
        return $query->where('fase_busqueda', $fase);
    }

    public function scopeExitosas($query)
    {
        return $query->where('http_code', 200);
    }
}
