<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pui_coincidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pui_reporte_id')->constrained('pui_reportes')->cascadeOnDelete();
            $table->string('fase_busqueda', 10); // 1|2|3
            $table->string('curp', 18)->index();

            $table->json('nombre_completo')->nullable();
            $table->json('domicilio')->nullable();
            $table->json('evento')->nullable();
            $table->json('payload_enviado')->nullable();
            $table->json('respuesta_pui')->nullable();

            $table->integer('http_code')->nullable();
            $table->timestamp('notificado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pui_coincidencias');
    }
};
