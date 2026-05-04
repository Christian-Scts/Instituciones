<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa_clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();

            $table->string('curp', 18)->index();
            $table->string('nombre', 50)->nullable();
            $table->string('primer_apellido', 50)->nullable();
            $table->string('segundo_apellido', 50)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('lugar_nacimiento', 20)->nullable();
            $table->string('sexo_asignado', 1)->nullable();

            $table->string('telefono', 15)->nullable();
            $table->string('correo', 50)->nullable();

            $table->string('direccion', 500)->nullable();
            $table->string('calle', 50)->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('colonia', 50)->nullable();
            $table->string('codigo_postal', 5)->nullable();
            $table->string('municipio_o_alcaldia', 100)->nullable();
            $table->string('entidad_federativa', 40)->nullable();

            $table->string('tipo_evento', 500)->nullable();
            $table->date('fecha_evento')->nullable();
            $table->string('descripcion_lugar_evento', 500)->nullable();
            $table->json('direccion_evento_json')->nullable();

            $table->json('domicilio_json')->nullable();
            $table->json('fotos_json')->nullable();
            $table->string('formato_fotos', 20)->nullable();
            $table->json('huellas_json')->nullable();
            $table->string('formato_huellas', 50)->nullable();

            $table->date('fecha_referencia')->nullable();

            $table->timestamps();

            $table->unique(['empresa_id', 'curp', 'fecha_referencia'], 'empresa_curp_fecha_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa_clientes');
    }
};