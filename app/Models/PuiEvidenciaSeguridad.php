<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuiEvidenciaSeguridad extends Model
{
    protected $table = 'pui_evidencia_seguridad';

    protected $fillable = [
        'empresa_id',
        'tipo_reporte',
        'herramienta',
        'version_herramienta',
        'ambiente_ejecucion',
        'fecha_ejecucion',
        'urls_validadas',
        'resultado_global',
        'archivo',
        'observaciones',
    ];

    protected $casts = [
        'urls_validadas' => 'array',
        'fecha_ejecucion' => 'datetime',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo_reporte', $tipo);
    }

    public function scopeAmbiente($query, string $ambiente)
    {
        return $query->where('ambiente_ejecucion', $ambiente);
    }

    public function scopeAprobados($query)
    {
        return $query->where('resultado_global', 'sin_vulnerabilidades');
    }
}