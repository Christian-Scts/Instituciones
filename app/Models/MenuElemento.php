<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuElemento extends Model
{
    protected $table = 'menu_elementos';

    protected $fillable = [
        'menu_grupo_id',
        'titulo',
        'slug',
        'route_name',
        'url',
        'icono',
        'permission_slug',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(MenuGrupo::class, 'menu_grupo_id');
    }
}