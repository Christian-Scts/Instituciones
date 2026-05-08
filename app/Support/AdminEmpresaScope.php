<?php

namespace App\Support;

use App\Models\Empresa;

class AdminEmpresaScope
{
    public static function empresaId(): ?int
    {
        return session('admin_user.empresa_id');
    }

    public static function esGlobal(): bool
    {
        return is_null(self::empresaId());
    }

    public static function validarEmpresa(int $empresaId): void
    {
        $empresaSesionId = self::empresaId();

        if ($empresaSesionId && (int) $empresaSesionId !== (int) $empresaId) {
            abort(403);
        }
    }

    public static function filtrarEmpresas($query)
    {
        $empresaId = self::empresaId();

        return $query->when($empresaId, function ($q) use ($empresaId) {
            $q->where('id', $empresaId);
        });
    }

    public static function filtrarPorEmpresaId($query)
    {
        $empresaId = self::empresaId();

        return $query->when($empresaId, function ($q) use ($empresaId) {
            $q->where('empresa_id', $empresaId);
        });
    }
}