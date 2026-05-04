<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pui_evidencia_seguridad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->string('tipo_reporte', 20); // SAST|DAST|SCA
            $table->string('herramienta')->nullable();
            $table->string('version_herramienta')->nullable();
            $table->string('ambiente_ejecucion', 20)->default('productivo');
            $table->dateTime('fecha_ejecucion')->nullable();
            $table->json('urls_validadas')->nullable();
            $table->string('resultado_global', 30)->nullable();
            $table->string('archivo')->nullable();
            $table->longText('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pui_evidencia_seguridad');
    }
};
