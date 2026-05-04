<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('rfc', 13)->unique();
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('slug')->unique();
            $table->string('giro')->nullable();

            $table->string('ambiente', 20)->default('sandbox'); // sandbox|productivo
            $table->string('ip_registrada', 45)->nullable();
            $table->string('url_base_api')->nullable();

            $table->string('endpoint_user')->nullable();
            $table->text('endpoint_password_hash')->nullable();

            $table->string('pui_user')->nullable();
            $table->text('pui_password_encrypted')->nullable();

            $table->text('jwt_secret_encrypted')->nullable();
            $table->string('jwt_algo', 20)->default('HS256');

            $table->string('folio_inscripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->boolean('aprobado_sandbox')->default(false);
            $table->boolean('aprobado_productivo')->default(false);

            $table->timestamp('ultimo_login_ok_en')->nullable();
            $table->timestamp('ultima_prueba_webhook_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
