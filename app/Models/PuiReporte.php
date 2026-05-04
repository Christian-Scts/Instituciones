<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property Empresa|null $empresa
 */

class PuiReporte extends Model
{
    protected $table = 'pui_reportes';

    protected $fillable = [
        'empresa_id',
        'id_busqueda',
        'curp',
        'fecha_desaparicion',
        'carpeta_investigacion',
        'fase_actual',
        'estatus',
        'es_prueba',
        'payload_original',
        'alta_en',
        'baja_en',
        'busqueda_historica_finalizada_en',
        'ultima_busqueda_continua_en',
    ];

    protected $casts = [
        'payload_original' => 'array',
        'es_prueba' => 'boolean',
        'fecha_desaparicion' => 'date',
        'alta_en' => 'datetime',
        'baja_en' => 'datetime',
        'busqueda_historica_finalizada_en' => 'datetime',
        'ultima_busqueda_continua_en' => 'datetime',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function coincidencias(): HasMany
    {
        return $this->hasMany(PuiCoincidencia::class, 'pui_reporte_id');
    }

    public function scopeActivos($query)
    {
        return $query->whereNull('baja_en')
            ->whereIn('estatus', [
                'activo',
                'activo_prueba',
                'fase_2_completada',
                'monitoreo_continuo',
            ]);
    }

    public function scopeReales($query)
    {
        return $query->where('es_prueba', false);
    }

    public function scopePrueba($query)
    {
        return $query->where('es_prueba', true);
    }

    public function estaActivo(): bool
    {
        return is_null($this->baja_en) && in_array($this->estatus, [
            'activo',
            'activo_prueba',
            'fase_2_completada',
            'monitoreo_continuo',
        ], true);
    }

    public function estaDesactivado(): bool
    {
        return $this->estatus === 'desactivado' || !is_null($this->baja_en);
    }

    public function esMonitoreoContinuo(): bool
    {
        return $this->estatus === 'monitoreo_continuo';
    }

    public function marcarDesactivado(): void
    {
        $this->update([
            'estatus' => 'desactivado',
            'baja_en' => now(),
        ]);
    }

    public function marcarFase1(): void
    {
        $this->update([
            'fase_actual' => '1',
            'estatus' => $this->es_prueba ? 'activo_prueba' : 'activo',
        ]);
    }

    public function marcarFase2Completada(): void
    {
        $this->update([
            'fase_actual' => '2',
            'estatus' => 'fase_2_completada',
        ]);
    }

    public function marcarFase3Monitoreo(): void
    {
        $this->update([
            'fase_actual' => '3',
            'estatus' => 'monitoreo_continuo',
        ]);
    }
}