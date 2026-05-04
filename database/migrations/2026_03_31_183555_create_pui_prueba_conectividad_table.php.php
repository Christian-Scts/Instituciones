<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pui_prueba_conectividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->cascadeOnDelete();
            $table->string('tipo_prueba', 50); // login, webhook, tls, zap, sast, sca
            $table->string('url')->nullable();
            $table->json('request_json')->nullable();
            $table->json('response_json')->nullable();
            $table->integer('http_code')->nullable();
            $table->boolean('exitosa')->default(false);
            $table->timestamp('ejecutada_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pui_prueba_conectividad');
    }
};
