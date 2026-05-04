<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuiLog extends Model
{
    protected $table = 'pui_logs';

    protected $fillable = [
        'empresa_id',
        'endpoint',
        'metodo',
        'request_id',
        'id_busqueda',
        'fase_busqueda',
        'headers_json',
        'body_json',
        'response_json',
        'http_code',
        'duracion_ms',
        'ip_origen',
        'user_agent',
        'error',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}