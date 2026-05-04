<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pui_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->nullable()->constrained()->nullOnDelete();
            $table->string('endpoint', 120);
            $table->string('metodo', 10)->nullable();
            $table->string('request_id', 100)->nullable()->index();
            $table->string('id_busqueda', 100)->nullable()->index();
            $table->string('fase_busqueda', 10)->nullable();
            $table->longText('headers_json')->nullable();
            $table->longText('body_json')->nullable();
            $table->longText('response_json')->nullable();
            $table->integer('http_code')->nullable();
            $table->integer('duracion_ms')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pui_logs');
    }
};
