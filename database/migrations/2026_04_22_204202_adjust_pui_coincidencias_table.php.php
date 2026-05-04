<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pui_coincidencias', function (Blueprint $table) {
            if (!Schema::hasColumn('pui_coincidencias', 'empresa_id')) {
                $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('pui_coincidencias', 'id_busqueda')) {
                $table->string('id_busqueda', 100)->nullable()->after('empresa_id');
            }

            if (!Schema::hasColumn('pui_coincidencias', 'exitosa')) {
                $table->boolean('exitosa')->default(false)->after('http_code');
            }
        });

        if (
            Schema::hasTable('pui_reportes') &&
            Schema::hasColumn('pui_coincidencias', 'pui_reporte_id') &&
            Schema::hasColumn('pui_coincidencias', 'empresa_id') &&
            Schema::hasColumn('pui_coincidencias', 'id_busqueda')
        ) {
            DB::statement("
                UPDATE pui_coincidencias pc
                INNER JOIN pui_reportes pr ON pr.id = pc.pui_reporte_id
                SET
                    pc.empresa_id = COALESCE(pc.empresa_id, pr.empresa_id),
                    pc.id_busqueda = COALESCE(pc.id_busqueda, pr.id_busqueda)
            ");
        }

        if (
            Schema::hasColumn('pui_coincidencias', 'http_code') &&
            Schema::hasColumn('pui_coincidencias', 'exitosa')
        ) {
            DB::statement("
                UPDATE pui_coincidencias
                SET exitosa = CASE
                    WHEN http_code >= 200 AND http_code < 300 THEN 1
                    ELSE 0
                END
            ");
        }

        Schema::table('pui_coincidencias', function (Blueprint $table) {
            // Solo índices nuevos
            try {
                $table->index('empresa_id');
            } catch (\Throwable $e) {
            }

            try {
                $table->index('id_busqueda');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        Schema::table('pui_coincidencias', function (Blueprint $table) {
            try {
                $table->dropIndex(['empresa_id']);
            } catch (\Throwable $e) {
            }

            try {
                $table->dropIndex(['id_busqueda']);
            } catch (\Throwable $e) {
            }

            if (Schema::hasColumn('pui_coincidencias', 'empresa_id')) {
                $table->dropColumn('empresa_id');
            }

            if (Schema::hasColumn('pui_coincidencias', 'id_busqueda')) {
                $table->dropColumn('id_busqueda');
            }

            if (Schema::hasColumn('pui_coincidencias', 'exitosa')) {
                $table->dropColumn('exitosa');
            }
        });
    }
};