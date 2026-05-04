<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pui_reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->string('id_busqueda', 100)->index();
            $table->string('curp', 18)->index();
            $table->date('fecha_desaparicion')->nullable();
            $table->string('carpeta_investigacion')->nullable();
            $table->string('fase_actual', 10)->nullable(); // 1|2|3
            $table->string('estatus', 30)->default('recibido');
            $table->boolean('es_prueba')->default(false);
            $table->json('payload_original')->nullable();

            $table->timestamp('alta_en')->nullable();
            $table->timestamp('baja_en')->nullable();
            $table->timestamp('busqueda_historica_finalizada_en')->nullable();
            $table->timestamp('ultima_busqueda_continua_en')->nullable();

            $table->timestamps();
            $table->unique(['empresa_id', 'id_busqueda']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pui_reportes');
    }
};
