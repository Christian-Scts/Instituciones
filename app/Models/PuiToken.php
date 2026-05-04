<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PuiToken extends Model
{
    protected $table = 'pui_tokens';

    protected $fillable = [
        'empresa_id',
        'token_hash',
        'emitido_en',
        'expira_en',
        'estatus',
    ];

    protected $casts = [
        'emitido_en' => 'datetime',
        'expira_en' => 'datetime',
    ];

    
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    
    public function scopeActivos($query)
    {
        return $query->where('estatus', 'activo')
                     ->where('expira_en', '>', now());
    }

    
    public function estaVigente(): bool
    {
        return $this->estatus === 'activo' && $this->expira_en > now();
    }
}