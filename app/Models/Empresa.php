<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $fillable = [
        'rfc',
        'razon_social',
        'nombre_comercial',
        'slug',
        'giro',
        'ambiente',
        'ip_registrada',
        'url_base_api',
        'ip_whitelist',
        'rate_limit_per_minute',

        // Credenciales internas
        'endpoint_user',
        'endpoint_password_hash',
        'endpoint_password_encrypted',

        // Credenciales externas
        'pui_user',
        'pui_password_encrypted',

        'jwt_secret_encrypted',
        'jwt_algo',

        'folio_inscripcion',
        'activo',
        'aprobado_sandbox',
        'aprobado_productivo',
        'ultimo_login_ok_en',
        'ultima_prueba_webhook_en',
    ];

    protected $casts = [
        'ip_whitelist' => 'array',
        'activo' => 'boolean',
        'aprobado_sandbox' => 'boolean',
        'aprobado_productivo' => 'boolean',
        'ultimo_login_ok_en' => 'datetime',
        'ultima_prueba_webhook_en' => 'datetime',
    ];

    public function tokens(): HasMany
    {
        return $this->hasMany(PuiToken::class);
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(PuiReporte::class);
    }

    public function coincidencias(): HasMany
    {
        return $this->hasMany(PuiCoincidencia::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PuiLog::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(EmpresaCliente::class);
    }

    public function usuarios(): HasMany
{
    return $this->hasMany(Usuario::class);
}
}