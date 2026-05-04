<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuGrupo extends Model
{
    protected $table = 'menu_grupos';

    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function elementos(): HasMany
    {
        return $this->hasMany(MenuElemento::class, 'menu_grupo_id')
            ->orderBy('orden');
    }
}