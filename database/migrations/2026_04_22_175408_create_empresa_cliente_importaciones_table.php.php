<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_cliente_importaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();

            $table->string('archivo_original')->nullable();
            $table->string('archivo_path');
            $table->string('extension', 10)->nullable();

            $table->unsignedInteger('total_filas')->default(0);
            $table->unsignedInteger('filas_ok')->default(0);
            $table->unsignedInteger('filas_error')->default(0);

            $table->string('estatus', 30)->default('pendiente'); // pendiente, procesando, completada, completada_con_errores, error
            $table->json('errores_json')->nullable();
            $table->text('mensaje_error')->nullable();

            $table->boolean('sobrescribir')->default(false);
            $table->timestamp('iniciado_en')->nullable();
            $table->timestamp('terminado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_cliente_importaciones');
    }
};