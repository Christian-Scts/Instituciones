<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuiPruebaConectividad extends Model
{
    protected $table = 'pui_prueba_conectividad';

    protected $fillable = [
        'empresa_id',
        'tipo_prueba',
        'url',
        'request_json',
        'response_json',
        'http_code',
        'exitosa',
        'ejecutada_en',
    ];

    protected $casts = [
        'request_json' => 'array',
        'response_json' => 'array',
        'exitosa' => 'boolean',
        'ejecutada_en' => 'datetime',
    ];

    
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeTipo($query, string $tipo)
    {
        return $query->where('tipo_prueba', $tipo);
    }

    public function scopeExitosas($query)
    {
        return $query->where('exitosa', true);
    }

    public function scopeUltima($query)
    {
        return $query->orderByDesc('ejecutada_en');
    }
}